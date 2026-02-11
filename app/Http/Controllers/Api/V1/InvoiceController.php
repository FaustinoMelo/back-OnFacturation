<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Tenant\Invoice;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoiceService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $invoices = Invoice::with(['customer', 'items.product'])
            ->when($request->has('status'), function ($query) use ($request): void {
                $query->where('status', $request->status);
            })
            ->when($request->has('customer_id'), function ($query) use ($request): void {
                $query->where('customer_id', $request->customer_id);
            })
            ->when($request->has('date_from'), function ($query) use ($request): void {
                $query->where('date', '>=', $request->date_from);
            })
            ->when($request->has('date_to'), function ($query) use ($request): void {
                $query->where('date', '<=', $request->date_to);
            })
            ->orderBy('date', 'desc')
            ->paginate($request->get('per_page', 20));

        return InvoiceResource::collection($invoices);
    }

    public function store(Request $request): InvoiceResource
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'payment_term_id' => 'nullable|exists:payment_terms,id',
            'type' => 'nullable|in:normal,simplified,export',
            'currency' => 'nullable|string|size:3',
            'exchange_rate' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        $invoice = $this->invoiceService->create($validated);

        return InvoiceResource::make($invoice);
    }

    public function show(Invoice $invoice): InvoiceResource
    {
        $invoice->load(['customer', 'items.product', 'payments']);

        return InvoiceResource::make($invoice);
    }

    public function update(Request $request, Invoice $invoice): InvoiceResource
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'date' => 'sometimes|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($validated);

        return InvoiceResource::make($invoice->load(['customer', 'items.product']));
    }

    public function destroy(Invoice $invoice): \Illuminate\Http\JsonResponse
    {
        if ($invoice->status->value !== 'draft') {
            return response()->json(['message' => 'Apenas faturas em rascunho podem ser eliminadas'], 422);
        }

        $invoice->delete();

        return response()->json(['message' => 'Fatura eliminada com sucesso']);
    }

    public function issue(Invoice $invoice): InvoiceResource
    {
        $invoice = $this->invoiceService->issue($invoice);

        return InvoiceResource::make($invoice->load(['customer', 'items.product']));
    }
}

