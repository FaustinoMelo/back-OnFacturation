<?php

namespace App\Modules\Tax\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaxResource;
use App\Modules\Tax\Models\Tax;
use App\Modules\Tax\Requests\StoreTaxRequest;
use App\Modules\Tax\Requests\UpdateTaxRequest;
use App\Modules\Tax\Services\TaxService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaxController extends Controller
{
    public function __construct(private TaxService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'is_active', 'per_page']);
        $taxes = $this->service->getFiltered($filters);

        return TaxResource::collection($taxes);
    }

    public function store(StoreTaxRequest $request): TaxResource
    {
        $tax = $this->service->create($request->validated());

        return TaxResource::make($tax);
    }

    public function show(Tax $tax): TaxResource
    {
        $loaded = $this->service->getById($tax);

        return TaxResource::make($loaded);
    }

    public function update(UpdateTaxRequest $request, Tax $tax): TaxResource
    {
        $updated = $this->service->update($tax, $request->validated());

        return TaxResource::make($updated);
    }

    public function destroy(Tax $tax): JsonResponse
    {
        $this->service->delete($tax);

        return response()->json(['message' => 'Imposto eliminado com sucesso']);
    }
}
