<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
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
        Route::get('/all', [OrderController::class, 'all'])->middleware('admin');
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/detail/{id}', [OrderController::class, 'detail']);
        Route::post('/', [OrderController::class, 'create']);
        Route::patch('/{id}', [OrderController::class, 'edit'])->middleware('admin');
        Route::delete('/{id}', [OrderController::class, 'remove'])->middleware('admin');
    });

    Route::prefix('brand')->group(function () {
        Route::post('/', [BrandController::class, 'create'])->middleware('admin');
        Route::patch('/{id}', [BrandController::class, 'edit'])->middleware('admin');
        Route::delete('/{id}', [BrandController::class, 'remove'])->middleware('admin');
    });

    Route::prefix('category')->group(function () {
        Route::post('/', [CategoryController::class, 'create'])->middleware('admin');
        Route::patch('/{id}', [CategoryController::class, 'edit'])->middleware('admin');
        Route::delete('/{id}', [CategoryController::class, 'remove'])->middleware('admin');
    });

    Route::prefix('product')->group(function () {
        Route::post('/', [ProductController::class, 'create'])->middleware('admin');
        Route::post('/{id}', [ProductController::class, 'edit'])->middleware('admin');
        Route::delete('/{id}', [ProductController::class, 'remove'])->middleware('admin');
    });


    Route::prefix('blog')->group(function () {
        Route::post('/', [BlogController::class, 'create'])->middleware('admin');
        Route::post('/{id}', [BlogController::class, 'edit'])->middleware('admin');
        Route::delete('/{id}', [BlogController::class, 'remove'])->middleware('admin');
    });

    Route::prefix('shipping')->group(function () {
        Route::post('/', [ShippingController::class, 'index'])->middleware('admin');
    });

    Route::prefix('report')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->middleware('admin');
        Route::get('/order', [ReportController::class, 'order'])->middleware('admin');
    });

    Route::prefix('newsletter')->group(function () {
        Route::get('/', [NewsletterController::class, 'index'])->middleware('admin');
        Route::delete('/{id}', [NewsletterController::class, 'remove'])->middleware('admin');
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('admin');
        Route::get('/{id}', [UserController::class, 'detail'])->middleware('admin');
        Route::post('/', [UserController::class, 'create'])->middleware('admin');
        Route::post('/permission', [UserController::class, 'permission'])->middleware('admin');
        Route::patch('/{id}', [UserController::class, 'edit'])->middleware('admin');
        Route::delete('/{id}', [UserController::class, 'remove'])->middleware('admin');
    });
    Route::prefix('faq')->group(function () {
        Route::get('/', [FaqController::class, 'index'])->middleware('admin');
        Route::get('/detail/{id}', [FaqController::class, 'detail'])->middleware('admin');
        Route::post('/', [FaqController::class, 'create'])->middleware('admin');
        Route::patch('/{id}', [FaqController::class, 'edit'])->middleware('admin');
        Route::delete('/{id}', [FaqController::class, 'remove'])->middleware('admin');
    });
    Route::prefix('social-media')->group(function () {
        Route::post('/', [SocialMediaController::class, 'create']);
    });
});

Route::prefix('faq')->group(function () {
    Route::get('/all', [FaqController::class, 'all']);
});
Route::prefix('social-media')->group(function () {
    Route::get('/', [SocialMediaController::class, 'index']);
});

Route::prefix('brand')->group(function () {
    Route::get('/', [BrandController::class, 'all']);
    Route::get('/{id}', [BrandController::class, 'detail']);
});
Route::prefix('newsletter')->group(function () {
    Route::post('/', [NewsletterController::class, 'create']);
});

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'all']);
    Route::get('/{id}', [CategoryController::class, 'detail']);
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'all']);
    Route::get('/{id}', [ProductController::class, 'detail']);
});

Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'all']);
    Route::get('/{id}', [BlogController::class, 'detail']);
});
