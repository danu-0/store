<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\kategoriController;
use App\Http\Controllers\Api\productController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user', [AuthController::class, 'index']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('me',                [AuthController::class, 'me']);
    Route::get('refresh',           [AuthController::class, 'refresh']);
    Route::get('logout',            [AuthController::class, 'logout']);

    Route::prefix('kategoris')->group(function () {
        Route::get('', [kategoriController::class, 'index']);
        Route::get('/{id}', [kategoriController::class, 'indexById']);
        Route::post('', [kategoriController::class, 'create']);
        Route::put('/{id}', [kategoriController::class, 'update']);
        Route::delete('/{id}', [kategoriController::class, 'delete']);
    });

    Route::prefix('product')->group(function () {
        Route::get('', [productController::class, 'index']);
        Route::get('/{id}', [productController::class, 'indexById']);
        Route::post('', [productController::class, 'create']);
        Route::put('/{id}', [productController::class, 'update']);
        Route::delete('/{id}', [productController::class, 'delete']);
    });
});
