<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'category_id', 'type', 'is_active', 'per_page']);
        $products = $this->service->getFiltered($filters);

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = $this->service->create($request->validated());

        return ProductResource::make($product);
    }

    public function show(Product $product): ProductResource
    {
        $loadedProduct = $this->service->getById($product);

        return ProductResource::make($loadedProduct);
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $updatedProduct = $this->service->update($product, $request->validated());

        return ProductResource::make($updatedProduct);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->service->delete($product);

        return response()->json(['message' => 'Produto eliminado com sucesso']);
    }
}
