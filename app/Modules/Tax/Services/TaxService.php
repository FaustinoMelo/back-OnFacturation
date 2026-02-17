<?php

namespace App\Modules\Tax\Services;

use App\Modules\Tax\Models\Tax;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TaxService
{
    public function __construct(private Tax $tax) {}

    public function getFiltered(array $filters): LengthAwarePaginator
    {
        return $this->tax->query()
            ->when($filters['search'] ?? null, function (Builder $q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when(array_key_exists('is_active', $filters), function (Builder $q, $isActive) {
                $q->where('is_active', (bool) $isActive);
            }, fn (Builder $q) => $q)
            ->orderBy('name')
            ->paginate($filters['per_page'] ?? 20);
    }

    public function create(array $data): Tax
    {
        return $this->tax->create($data);
    }

    public function getById(Tax $tax): Tax
    {
        return $tax;
    }

    public function update(Tax $tax, array $data): Tax
    {
        $tax->update($data);

        return $tax;
    }

    public function delete(Tax $tax): bool
    {
        return $tax->delete();
    }
}
