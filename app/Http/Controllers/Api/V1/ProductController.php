<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::with(['tax', 'category'])
            ->when($request->has('search'), function ($query) use ($request): void {
                $query->where(function ($q) use ($request): void {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('sku', 'like', "%{$request->search}%");
                });
            })
            ->when($request->has('category_id'), function ($query) use ($request): void {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->has('type'), function ($query) use ($request): void {
                $query->where('type', $request->type);
            })
            ->when($request->has('is_active'), function ($query) use ($request): void {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->orderBy('name')
            ->paginate($request->get('per_page', 20));

        return ProductResource::collection($products);
    }

    public function store(Request $request): ProductResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'type' => 'nullable|in:product,service',
            'tax_id' => 'nullable|exists:taxes,id',
            'category_id' => 'nullable|exists:categories,id',
            'track_stock' => 'nullable|boolean',
            'has_variants' => 'nullable|boolean',
        ]);

        $product = Product::create($validated);

        return ProductResource::make($product->load(['tax', 'category']));
    }

    public function show(Product $product): ProductResource
    {
        return ProductResource::make($product->load(['tax', 'category']));
    }

    public function update(Request $request, Product $product): ProductResource
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'type' => 'nullable|in:product,service',
            'tax_id' => 'nullable|exists:taxes,id',
            'category_id' => 'nullable|exists:categories,id',
            'track_stock' => 'nullable|boolean',
            'has_variants' => 'nullable|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        $product->update($validated);

        return ProductResource::make($product->load(['tax', 'category']));
    }

    public function destroy(Product $product): \Illuminate\Http\JsonResponse
    {
        $product->delete();

        return response()->json(['message' => 'Produto eliminado com sucesso']);
    }
}

