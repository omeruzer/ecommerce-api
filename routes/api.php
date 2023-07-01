<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::patch('user-update', [AuthController::class, 'authUserUpdate']);
    Route::patch('user-password', [AuthController::class, 'userPass']);

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/action', [CartController::class, 'action']);
    });

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'detail']);
        Route::post('/', [OrderController::class, 'create']);
        Route::patch('/{id}', [OrderController::class, 'edit']);
        Route::delete('/{id}', [OrderController::class, 'remove']);
    });
});

Route::prefix('brand')->group(function () {
    Route::get('/', [BrandController::class, 'all']);
    Route::get('/{id}', [BrandController::class, 'detail']);
    Route::post('/', [BrandController::class, 'create']);
    Route::patch('/{id}', [BrandController::class, 'edit']);
    Route::delete('/{id}', [BrandController::class, 'remove']);
});

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'all']);
    Route::get('/{id}', [CategoryController::class, 'detail']);
    Route::post('/', [CategoryController::class, 'create']);
    Route::patch('/{id}', [CategoryController::class, 'edit']);
    Route::delete('/{id}', [CategoryController::class, 'remove']);
});
Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'all']);
    Route::get('/{id}', [ProductController::class, 'detail']);
    Route::post('/', [ProductController::class, 'create']);
    Route::post('/{id}', [ProductController::class, 'edit']);
    Route::delete('/{id}', [ProductController::class, 'remove']);
});
Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'all']);
    Route::get('/{id}', [BlogController::class, 'detail']);
    Route::post('/', [BlogController::class, 'create']);
    Route::post('/{id}', [BlogController::class, 'edit']);
    Route::delete('/{id}', [BlogController::class, 'remove']);
});
