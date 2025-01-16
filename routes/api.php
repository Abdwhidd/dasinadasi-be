<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register'])->name('api.register');
Route::post('login', [AuthController::class, 'login'])->name('api.login');

// Protected routes (requires JWT authentication)
Route::middleware(['auth:api'])->group(function () {
    // Profile route
    Route::put('profile/update', [ProfileController::class, 'update']);

    // Article routes
    Route::get('articles', [ArticleController::class, 'index']);
    Route::post('articles', [ArticleController::class, 'store']);
    Route::get('articles/{id}', [ArticleController::class, 'show']);
    Route::put('articles/{id}', [ArticleController::class, 'update']);
    Route::delete('articles/{id}', [ArticleController::class, 'destroy']);
});

