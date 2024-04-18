<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\LessorController;
use App\Http\Controllers\LodgingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('bookings', BookingController::class);
Route::apiResource('lessors', LessorController::class);
Route::apiResource('lodgings', LodgingController::class);
