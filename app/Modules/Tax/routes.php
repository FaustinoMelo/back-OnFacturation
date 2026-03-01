<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Tax\Controllers\TaxController;

Route::middleware(['auth:api', 'tenant.isolation'])->prefix('v1')->group(function (): void {
    Route::apiResource('taxes', TaxController::class);
});
