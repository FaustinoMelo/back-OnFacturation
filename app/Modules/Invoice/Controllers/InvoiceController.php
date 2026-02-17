<?php

namespace App\Modules\Invoice\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Modules\Invoice\Models\Invoice;
use App\Modules\Invoice\Requests\StoreInvoiceRequest;
use App\Modules\Invoice\Requests\UpdateInvoiceRequest;
use App\Modules\Invoice\Requests\IssueInvoiceRequest;
use App\Modules\Invoice\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'status', 'per_page']);
        $invoices = $this->service->getFiltered($filters);

        return InvoiceResource::collection($invoices);
    }

    public function store(StoreInvoiceRequest $request): InvoiceResource
    {
        $invoice = $this->service->create($request->validated());

        return InvoiceResource::make($invoice);
    }

    public function show(Invoice $invoice): InvoiceResource
    {
        $loaded = $this->service->getById($invoice);

        return InvoiceResource::make($loaded);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): InvoiceResource
    {
        $updated = $this->service->update($invoice, $request->validated());

        return InvoiceResource::make($updated);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $this->service->delete($invoice);

        return response()->json(['message' => 'Fatura eliminada com sucesso']);
    }

    public function issue(IssueInvoiceRequest $request, Invoice $invoice): InvoiceResource
    {
        $issued = $this->service->issue($invoice, $request->validated());

        return InvoiceResource::make($issued);
    }
}
