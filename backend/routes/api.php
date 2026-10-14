<?php

use App\Http\Controllers\Api\V1\Auth\CurrentUserController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\SessionController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/health', HealthController::class)->name('health');
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);

    Route::prefix('auth')->name('auth.')->group(function (): void {
        Route::post('/register', [RegistrationController::class, 'store'])->middleware('throttle:5,1')->name('register');
        Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:5,1')->name('login');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('/user', CurrentUserController::class)->name('user');
            Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
        });
    });
});
