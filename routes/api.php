<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// User Routes
Route::get('/user', [UserController::class, 'index']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Category Routes
Route::get('/category', [CategoryController::class, 'index']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/user')->group(function () {
        Route::post('/logout-currently', [UserController::class, 'logoutCurrent']);
        Route::post('/logoutall', [UserController::class, 'logoutAll']);
        Route::get('/profile', [UserController::class, 'profile']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
    });

    Route::prefix('/category')->group(function () {
        Route::post('/create', [CategoryController::class, 'store']);
        Route::post('/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    });
});
