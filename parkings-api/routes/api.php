<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/parkings', [ParkingController::class, 'store']);
Route::get('/parkings', [ParkingController::class, 'index']);
Route::get('/parkings/nearest', [ParkingController::class, 'nearest']);
Route::get('/parkings/{id}', [ParkingController::class, 'show']);
