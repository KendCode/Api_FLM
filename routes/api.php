<?php

use Illuminate\Support\Facades\Route; // <- Esta línea es necesaria
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CitaController;


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('citas', CitaController::class);
    Route::post('/logout',[AuthController::class,'logout']);
});


