<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PembayaranController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes for authentication, admin, and customer actions.
|
*/

// Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('user', [AuthController::class, 'index']);

// Protected Routes (Authenticated Users)
Route::middleware('auth:api')->group(function () {

    // Authenticated User Routes
    Route::get('me', [AuthController::class, 'me']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('logout', [AuthController::class, 'logout']);

    // ROUTE ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // KategoriS
        Route::prefix('kategoris')->group(function () {
            Route::get('', [KategoriController::class, 'index']);
            Route::get('{id}', [KategoriController::class, 'indexById']);
            Route::post('', [KategoriController::class, 'create']);
            Route::put('{id}', [KategoriController::class, 'update']);
            Route::delete('{id}', [KategoriController::class, 'delete']);
        });

        // Product
        Route::prefix('product')->group(function () {
            Route::get('', [ProductController::class, 'index']);
            Route::get('{id}', [ProductController::class, 'indexById']);
            Route::post('', [ProductController::class, 'create']);
            Route::put('{id}', [ProductController::class, 'update']);
            Route::delete('{id}', [ProductController::class, 'delete']);
        });

        // Pembayaran
        Route::get('pembayaran', [PembayaranController::class, 'index']);
        Route::delete('pembayaran/{id}', [PembayaranController::class, 'delete']);
    });

    // ROUTE CUSTOMER
    Route::middleware('role:customer')->prefix('customer')->group(function () {
        // Categories
        Route::prefix('kategoris')->group(function () {
            Route::get('', [KategoriController::class, 'index']);
            Route::get('{id}', [KategoriController::class, 'indexById']);
        });

        // Products
        Route::prefix('product')->group(function () {
            Route::get('', [ProductController::class, 'index']);
            Route::get('{id}', [ProductController::class, 'indexById']);
        });

        // Pembayaran
        Route::get('pembayaran', [PembayaranController::class, 'indexByUser']);
        Route::post('pembayaran', [PembayaranController::class, 'create']);
    });
});
