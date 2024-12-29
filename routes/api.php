<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
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

Route::apiResource('stores', StoreController::class);
Route::get('stores/{id}/products', [StoreController::class, 'products']);
