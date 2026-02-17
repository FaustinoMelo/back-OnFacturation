<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Controllers\AuthController;

// Public auth routes (no authentication required)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

// Protected auth routes (authentication required)
Route::middleware('auth:api')->group(function (): void {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});
