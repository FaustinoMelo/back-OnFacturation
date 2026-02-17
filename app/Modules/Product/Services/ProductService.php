<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    public function __construct(private Product $product) {}

    public function getFiltered(array $filters): LengthAwarePaginator
    {
        return $this->product
            ->query()
            ->with(['tax', 'category'])
            ->when($filters['search'] ?? null, $this->applySearchFilter(...))
            ->when($filters['category_id'] ?? null, $this->applyCategoryFilter(...))
            ->when($filters['type'] ?? null, $this->applyTypeFilter(...))
            ->when(array_key_exists('is_active', $filters), $this->applyActiveFilter(...), fn (Builder $q) => $q)
            ->orderBy('name')
            ->paginate($filters['per_page'] ?? 20);
    }

    public function create(array $data): Product
    {
        return $this->product->create($data);
    }

    public function getById(Product $product): Product
    {
        return $product->load(['tax', 'category']);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->load(['tax', 'category']);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    private function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search): void {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    private function applyCategoryFilter(Builder $query, mixed $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    private function applyTypeFilter(Builder $query, mixed $type): Builder
    {
        return $query->where('type', $type);
    }

    private function applyActiveFilter(Builder $query, mixed $isActive): Builder
    {
        return $query->where('is_active', (bool) $isActive);
    }
}
