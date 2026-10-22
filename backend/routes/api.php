<?php

use App\Http\Controllers\Api\V1\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Api\V1\Admin\OrderVerificationController as AdminOrderVerificationController;
use App\Http\Controllers\Api\V1\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Api\V1\Auth\CurrentUserController;
use App\Http\Controllers\Api\V1\Auth\EmailVerificationController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\SessionController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CartItemController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\CustomerAddressController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Middleware\ResolveCart;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/health', HealthController::class)->name('health');
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);

    Route::prefix('auth')->name('auth.')->group(function (): void {
        Route::post('/register', [RegistrationController::class, 'store'])->middleware('throttle:5,1')->name('register');
        Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:5,1')->name('login');
        Route::post('/forgot-password', [PasswordResetController::class, 'store'])->middleware('throttle:5,1')->name('password.email');
        Route::post('/reset-password', [PasswordResetController::class, 'update'])->middleware('throttle:5,1')->name('password.update');
        Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'update'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('/user', CurrentUserController::class)->name('user');
            Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
            Route::post('/email/verification-notification', [EmailVerificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
        });
    });

    Route::middleware(ResolveCart::class)->group(function (): void {
        Route::get('cart', [CartController::class, 'show'])->name('cart.show');
        Route::post('cart/items', [CartItemController::class, 'store'])->name('cart.items.store');
        Route::match(['put', 'patch'], 'cart/items/{item}', [CartItemController::class, 'update'])->name('cart.items.update');
        Route::delete('cart/items/{item}', [CartItemController::class, 'destroy'])->name('cart.items.destroy');
        Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{orderNumber}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::apiResource('me/addresses', CustomerAddressController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('me.addresses');
        Route::get('me/orders', [OrderController::class, 'index'])->name('me.orders.index');

        Route::prefix('admin')->name('admin.')->middleware('staff')->group(function (): void {
            Route::get('orders', [AdminOrderVerificationController::class, 'index'])->name('orders.index');
            Route::post('orders/{order}/confirm', [AdminOrderVerificationController::class, 'confirm'])->name('orders.confirm');
            Route::post('orders/{order}/decline', [AdminOrderVerificationController::class, 'decline'])->name('orders.decline');
            Route::post('orders/{order}/fulfill', [AdminOrderVerificationController::class, 'fulfill'])->name('orders.fulfill');
            Route::post('orders/{order}/cancel', [AdminOrderVerificationController::class, 'cancel'])->name('orders.cancel');
            Route::get('inventory/low-stock', [AdminInventoryController::class, 'lowStock'])->name('inventory.low-stock');
            Route::post('inventory/{inventoryItem}/adjust', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust');
            Route::post('products/{product}/images', [AdminProductImageController::class, 'store'])->name('products.images.store');
            Route::delete('products/{product}/images/{image}', [AdminProductImageController::class, 'destroy'])->name('products.images.destroy');
        });
    });
});
