<?php

use Illuminate\Support\Facades\Route;

// Rotas públicas (v1) - sem autenticação
Route::prefix('v1')->group(function (): void {
    foreach (glob(app_path('Modules/*/routes.php')) as $routeFile) {
        require $routeFile;
    }
});

// Rotas protegidas (v1) - com autenticação e isolamento multi-tenant
Route::middleware(['auth:sanctum', 'tenant.isolation'])->prefix('v1')->group(function (): void {
    // rotas centrais mantidas aqui, se necessário
});