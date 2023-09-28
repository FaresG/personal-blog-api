<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);


// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', \App\Http\Controllers\PostController::class);
    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});
