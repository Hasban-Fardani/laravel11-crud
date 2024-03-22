<?php

use App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('posts', Api\PostController::class);

// AUTH
Route::post('/login', Api\Auth\LoginController::class);
Route::post('/logout', Api\Auth\LogoutController::class)->middleware('auth:sanctum');