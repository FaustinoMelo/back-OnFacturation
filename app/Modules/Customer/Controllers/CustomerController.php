<?php

namespace App\Modules\Customer\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Requests\StoreCustomerRequest;
use App\Modules\Customer\Requests\UpdateCustomerRequest;
use App\Modules\Customer\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    public function __construct(private CustomerService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'is_active', 'per_page']);
        $customers = $this->service->getFiltered($filters);

        return CustomerResource::collection($customers);
    }

    public function store(StoreCustomerRequest $request): CustomerResource
    {
        $customer = $this->service->create($request->validated());

        return CustomerResource::make($customer);
    }

    public function show(Customer $customer): CustomerResource
    {
        $loadedCustomer = $this->service->getById($customer);

        return CustomerResource::make($loadedCustomer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {
        $updatedCustomer = $this->service->update($customer, $request->validated());

        return CustomerResource::make($updatedCustomer);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $this->service->delete($customer);

        return response()->json(['message' => 'Cliente eliminado com sucesso']);
    }
}
