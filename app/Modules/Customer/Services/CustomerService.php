<?php

namespace App\Modules\Customer\Services;

use App\Modules\Customer\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CustomerService
{
    public function __construct(private Customer $customer) {}

    public function getFiltered(array $filters): LengthAwarePaginator
    {
        return $this->customer
            ->query()
            ->when($filters['search'] ?? null, $this->applySearchFilter(...))
            ->when(array_key_exists('is_active', $filters), $this->applyActiveFilter(...), fn (Builder $q) => $q)
            ->orderBy('name')
            ->paginate($filters['per_page'] ?? 20);
    }

    public function create(array $data): Customer
    {
        return $this->customer->create($data);
    }

    public function getById(Customer $customer): Customer
    {
        return $customer;
    }

    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer;
    }

    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }

    private function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search): void {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('tax_id', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    private function applyActiveFilter(Builder $query, mixed $isActive): Builder
    {
        return $query->where('is_active', (bool) $isActive);
    }
}
