<?php

use Illuminate\Support\Facades\Route;
use App\Modules\User\Controllers\UserController;

Route::prefix('users')->middleware(['auth:api'])->group(function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::put('/me', [UserController::class, 'update']);
    Route::put('/me/password', [UserController::class, 'updatePassword']);
    Route::get('/', [UserController::class, 'index']);
});