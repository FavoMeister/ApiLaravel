<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // Si aplica

// Protected routes (JWT)
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/cars', [CarController::class, 'index']);
    Route::post('/crear-auto', [CarController::class, 'store']);
    Route::get('/ver-auto/{id}', [CarController::class, 'show']);
    Route::put('/actualizar-auto/{id}', [CarController::class, 'update']);
    Route::delete('/eliminar-auto/{id}', [CarController::class, 'destroy']);
    
    // Ejemplo: Rutas protegidas
    //Route::apiResource('posts', 'PostController');
});