<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return 'Hello, API!';
});

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user/current', [UserController::class, 'currentUser']);

Route::apiResource('products', ProductController::class);
Route::get('stores/{id}/products', [StoreController::class, 'products']);

Route::apiResource('stores', StoreController::class);

Route::post('/search', [SearchController::class, 'search']);

Route::middleware('auth:sanctum')->apiResource('orders',OrderController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/sites', [SiteController::class, 'index']);
    Route::get('/sites/{site}', [SiteController::class, 'show']);
    Route::post('/sites', [SiteController::class, 'store']);
    Route::put('/sites/{site}', [SiteController::class, 'update']);
    Route::delete('/sites/{site}', [SiteController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->apiResource('wishlist',OrderController::class);
