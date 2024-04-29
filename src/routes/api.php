<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\LessorController;
use App\Http\Controllers\LodgingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(
    function(){
        Route::resource('/booking',BookingController::class,['except'=>['create','edit']]);
        Route::resource('/lessor',LessorController::class,['except'=>['create','edit']]);
        Route::resource('/lodging',LodgingController::class,['except'=>['create','edit']]);
    }
);