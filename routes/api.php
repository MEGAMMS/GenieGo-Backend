<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return 'Hello, API!';
});

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->prefix('/user')->group(function () {
    Route::get('/current', [UserController::class, 'currentUser']);
    Route::put('/', [UserController::class, 'update']);
    Route::delete('/', [UserController::class, 'delete']);

});

Route::prefix('products')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/addTag/{product}', [ProductController::class, 'addTag']);
    });
});

Route::prefix('stores')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', [StoreController::class, 'index'])->name('stores.index');
    Route::get('/{store}', [StoreController::class, 'show'])->name('stores.show');
    Route::get('/{store}/products', [StoreController::class, 'products']);
    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [StoreController::class, 'store'])->name('stores.store');
        Route::put('/{store}', [StoreController::class, 'update'])->name('stores.update');
        Route::delete('/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');
        Route::post('/addTag/{store}', [StoreController::class, 'addTag']);
        
    });
});

Route::post('/search', [SearchController::class, 'search']);

Route::middleware('auth:sanctum')->apiResource('orders', OrderController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/wishlist/{productId}', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']);
    Route::get('/wishlist', [WishlistController::class, 'index']);
});

Route::middleware('auth:sanctum')->apiResource('sites', SiteController::class);

Route::middleware('auth:sanctum')->get('/tags', [TagController::class,'index']);
