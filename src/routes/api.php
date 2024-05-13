<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\LessorController;
use App\Http\Controllers\LodgingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;


Route::prefix('v1')->group(
    function () {
        Route::post('/user', [UserController::class, 'store']);
        Route::post('/lodging', [LodgingController::class, 'store']);

        Route::get('/user/getidentity', [UserController::class, 'getIdentity'])->middleware(ApiAuthMiddleware::class);
        Route::get('/user', [UserController::class, 'index']);
        Route::post('/user/login', [UserController::class, 'login']);

        Route::apiResource('/booking', BookingController::class, ['except' => ['create', 'edit']]);
        Route::apiResource('/lessor', LessorController::class, ['except' => ['create', 'edit']]);
        Route::apiResource('/lodging', LodgingController::class, ['except' => ['create', 'edit']]);


        Route::delete('/booking', [BookingController::class, 'destroy']);
        Route::delete('/lessor', [LessorController::class, 'destroy']);
        Route::delete('/lodging', [LodgingController::class, 'destroy']);
        Route::delete('/user/{name}', [UserController::class,'destroy']);

        Route::patch('/user/{name}', [UserController::class,'updatePartial']);
        Route::put('lodging', [LodgingController::class, 'update']);
        Route::put('booking', [BookingController::class, 'update']);
        Route::put('lessor', [LessorController::class, 'update']);
    }
);
