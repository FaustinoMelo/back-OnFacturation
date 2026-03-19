<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Product\Controllers\ProductController;

Route::middleware(['auth:api', 'tenant.isolation'])->prefix('v1')->group(function (): void {
    Route::apiResource('products', ProductController::class);
});
