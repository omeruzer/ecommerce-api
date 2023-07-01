<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
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
    Route::patch('/{id}', [ProductController::class, 'edit']);
    Route::delete('/{id}', [ProductController::class, 'remove']);
});
