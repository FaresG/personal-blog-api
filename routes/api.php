<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts', \App\Http\Controllers\PostController::class);
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('auth.register');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('auth.login');
