<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// User Routes
Route::get('/user', [UserController::class, 'index']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/user')->group(function () {
        Route::post('/logout-currently', [UserController::class, 'logoutCurrent']);
        Route::post('/logoutall', [UserController::class, 'logoutAll']);
        Route::get('/profile', [UserController::class, 'profile']);
    });
});
