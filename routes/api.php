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

Route::middleware('auth:sanctum')->apiResource('orders', OrderController::class);

Route::middleware('auth:sanctum')->apiResource('sites', SiteController::class);
