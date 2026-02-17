<?php

namespace App\Modules\Invoice\Services;

use App\Modules\Invoice\Models\Invoice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class InvoiceService
{
    public function __construct(private Invoice $invoice) {}

    public function getFiltered(array $filters): LengthAwarePaginator
    {
        return $this->invoice
            ->query()
            ->when($filters['search'] ?? null, function (Builder $q, $search) {
                $q->where('number', 'like', "%{$search}%");
            })
            ->when(array_key_exists('status', $filters), function (Builder $q, $status) {
                $q->where('status', $status);
            }, fn (Builder $q) => $q)
            ->orderBy('date', 'desc')
            ->paginate($filters['per_page'] ?? 20);
    }

    public function create(array $data): Invoice
    {
        // criar lógica de criação de fatura e items
        return $this->invoice->create($data);
    }

    public function getById(Invoice $invoice): Invoice
    {
        return $invoice->load(['items', 'customer', 'payments']);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);

        return $invoice->load(['items', 'customer', 'payments']);
    }

    public function delete(Invoice $invoice): bool
    {
        return $invoice->delete();
    }

    public function issue(Invoice $invoice, array $data): Invoice
    {
        $invoice->update(['status' => 'issued', 'issued_at' => $data['issued_at'] ?? now()]);

        return $invoice->refresh();
    }
}
