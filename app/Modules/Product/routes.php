<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Product\Controllers\ProductController;

Route::middleware(['auth:sanctum', 'tenant.isolation'])->prefix('v1')->group(function (): void {
    Route::apiResource('products', ProductController::class);
});
