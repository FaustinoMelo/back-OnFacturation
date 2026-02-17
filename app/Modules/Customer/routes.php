<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Customer\Controllers\CustomerController;

Route::middleware(['auth:sanctum', 'tenant.isolation'])->prefix('v1')->group(function (): void {
    Route::apiResource('customers', CustomerController::class);
});
