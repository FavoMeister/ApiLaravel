<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::post('login', [AuthController::class, 'login']);
//Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
//Route::resource('/api/cars', [CarController::class]);
