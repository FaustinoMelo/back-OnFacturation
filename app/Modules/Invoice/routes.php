<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Invoice\Controllers\InvoiceController;

Route::middleware(['auth:sanctum', 'tenant.isolation'])->prefix('v1')->group(function (): void {
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/issue', [InvoiceController::class, 'issue']);
});
