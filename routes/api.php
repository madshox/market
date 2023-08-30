<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BasketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('dashboard')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('products')->group(function () {
            Route::post('findAll', [ProductController::class, 'index']);
            Route::post('findOne', [ProductController::class, 'show']);
            Route::post('create', [ProductController::class, 'store']);
            Route::post('update', [ProductController::class, 'update']);
            Route::post('remove', [ProductController::class, 'destroy']);
        });

        Route::prefix('categories')->group(function () {
            Route::post('findAll', [CategoryController::class, 'index']);
            Route::post('findOne', [CategoryController::class, 'show']);
            Route::post('create', [CategoryController::class, 'store']);
            Route::post('update', [CategoryController::class, 'update']);
            Route::post('remove', [CategoryController::class, 'destroy']);
        });

        Route::prefix('basket')->group(function () {
            Route::post('/add', [BasketController::class, 'addProduct']);
            Route::post('/remove', [BasketController::class, 'removeProduct']);
        });
    });
});
