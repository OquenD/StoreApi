<?php

use App\Http\Controllers\Api\v1\AuthControlller;
use App\Http\Controllers\Api\v1\CartController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth
    Route::post('/register', [AuthControlller::class, 'register']);
    Route::post('/login', [AuthControlller::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthControlller::class, 'logout']);
        Route::get('/profile', [AuthControlller::class, 'profile']);
    });

    // Products (Public routes)
    Route::prefix('products')->group(function () {
        Route::get('/search', [ProductController::class, 'search']);
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Cart
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('/add', [CartController::class, 'addItem']);
            Route::put('/update/{cartItemId}', [CartController::class, 'updateItem']);
            Route::delete('/remove/{cartItemId}', [CartController::class, 'removeItem']);
        });

        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('/{id}', [OrderController::class, 'show']);
            Route::post('/create', [OrderController::class, 'create']);
        });
    });
});