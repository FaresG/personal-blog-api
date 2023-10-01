<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->middleware(['throttle:6,1']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Routes require a valid API Token with correct ablility to access

    // Users Routes (Admins can access that too)
    Route::middleware('abilities:admin,default')->group(function () {
        Route::apiResource('posts', \App\Http\Controllers\PostController::class);
        Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });

    // Admin Routes
    Route::middleware('ability:admin')->group(function () {
        Route::apiResource('users', \App\Http\Controllers\UserController::class);
    });
});

