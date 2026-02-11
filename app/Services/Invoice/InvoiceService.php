<?php

namespace App\Services\Invoice;

use App\Enums\DocumentType;
use App\Enums\InvoiceStatus;
use App\Models\Tenant\Invoice;
use App\Models\Tenant\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class InvoiceService
{
    public function __construct(
        private TaxCalculationService $taxService,
        private InvoiceNumberService $numberService
    ) {}

    public function create(array $data): Invoice
    {
        return DB::transaction(function () use ($data): Invoice {
            $date = isset($data['date']) ? new \DateTime($data['date']) : new \DateTime();
            $companyId = \App\Services\Tenant\TenantScope::getCompanyId();
            
            if (!$companyId) {
                throw new \Exception('Company ID não definido no TenantScope');
            }

            // Gerar número da fatura
            $number = $this->numberService->generateNextNumber(
                $companyId,
                DocumentType::Invoice,
                $date
            );

            $series = 'A'; // Pode ser configurável
            $year = (int) $date->format('Y');
            $fullNumber = $this->numberService->formatNumber($series, $year, $number);

            // Calcular totais
            $totals = $this->taxService->calculateTotals($data['items']);
            $data = array_merge($data, $totals);

            // Preparar dados da fatura
            $invoiceData = [
                'company_id' => $companyId,
                'customer_id' => $data['customer_id'],
                'sequence_id' => $this->getSequenceId($companyId, DocumentType::Invoice, $series, $year),
                'number' => $number,
                'full_number' => $fullNumber,
                'status' => InvoiceStatus::Draft,
                'type' => $data['type'] ?? 'normal',
                'date' => $date->format('Y-m-d'),
                'due_date' => $data['due_date'] ?? null,
                'payment_term_id' => $data['payment_term_id'] ?? null,
                'subtotal' => $totals['subtotal'],
                'tax_total' => $totals['tax_total'],
                'discount_total' => $totals['discount_total'],
                'total' => $totals['total'],
                'paid_amount' => 0.00,
                'currency' => $data['currency'] ?? 'EUR',
                'exchange_rate' => $data['exchange_rate'] ?? 1.000000,
                'notes' => $data['notes'] ?? null,
                'internal_notes' => $data['internal_notes'] ?? null,
                'created_by' => auth()->id(),
            ];

            // Criar fatura
            $invoice = Invoice::create($invoiceData);

            // Criar itens
            foreach ($data['items'] as $itemData) {
                $itemTotals = $this->taxService->calculateItemTotal($itemData);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $itemData['product_id'] ?? null,
                    'product_variant_id' => $itemData['product_variant_id'] ?? null,
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit' => $itemData['unit'] ?? 'un',
                    'unit_price' => $itemData['unit_price'],
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'discount_amount' => $itemTotals['discount_amount'],
                    'tax_rate' => $itemData['tax_rate'] ?? 0,
                    'tax_amount' => $itemTotals['tax_amount'],
                    'total' => $itemTotals['total'],
                ]);
            }

            // Gerar hash de segurança
            $this->generateSecurityHash($invoice);

            // Disparar eventos
            Event::dispatch('invoice.created', [$invoice]);

            return $invoice->load(['customer', 'items.product']);
        });
    }

    public function issue(Invoice $invoice): Invoice
    {
        return DB::transaction(function () use ($invoice): Invoice {
            if ($invoice->status !== InvoiceStatus::Draft) {
                throw new \Exception('Apenas faturas em rascunho podem ser emitidas');
            }

            $invoice->update([
                'status' => InvoiceStatus::Issued,
                'issued_at' => now(),
            ]);

            // Atualizar saldo do cliente
            $this->updateCustomerBalance($invoice);

            Event::dispatch('invoice.issued', [$invoice]);

            return $invoice->fresh();
        });
    }

    private function getSequenceId(int $companyId, DocumentType $documentType, string $series, int $year): int
    {
        $sequence = \App\Models\Tenant\DocumentSequence::where('company_id', $companyId)
            ->where('document_type', $documentType)
            ->where('series', $series)
            ->where('year', $year)
            ->first();

        if (!$sequence) {
            $sequence = \App\Models\Tenant\DocumentSequence::create([
                'company_id' => $companyId,
                'document_type' => $documentType,
                'series' => $series,
                'year' => $year,
                'current_number' => 1,
                'format' => '{series}/{year}/{number}',
                'is_active' => true,
            ]);
        }

        return $sequence->id;
    }

    private function generateSecurityHash(Invoice $invoice): void
    {
        $data = $invoice->toArray();
        $hash = hash('sha256', json_encode($data));
        $invoice->update(['hash' => $hash]);
    }

    private function updateCustomerBalance(Invoice $invoice): void
    {
        $customer = $invoice->customer;
        $customer->increment('current_balance', $invoice->total);
    }
}

