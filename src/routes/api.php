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
        Route::post('/user/login', [UserController::class, 'login']);
        Route::post('/lodging', [LodgingController::class, 'store'])->middleware(ApiAuthMiddleware::class);
        Route::post('/lessor', [LessorController::class, 'store'])->middleware(ApiAuthMiddleware::class);
        Route::post('/booking', [BookingController::class, 'store'])->middleware(ApiAuthMiddleware::class);

        Route::get('/user/getidentity', [UserController::class, 'getIdentity'])->middleware(ApiAuthMiddleware::class);
        Route::get('/user', [UserController::class, 'index']);

        Route::apiResource('/booking', BookingController::class, ['except' => ['create', 'edit']]);
        Route::apiResource('/lessor', LessorController::class, ['except' => ['create', 'edit']]);
        Route::apiResource('/lodging', LodgingController::class, ['except' => ['create', 'edit']]);

        Route::delete('/booking', [BookingController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/lessor', [LessorController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/lodging', [LodgingController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/user/{name}', [UserController::class,'destroy'])->middleware(ApiAuthMiddleware::class);

        Route::patch('/user/{name}', [UserController::class,'updatePartial'])->middleware(ApiAuthMiddleware::class);
        Route::put('lodging', [LodgingController::class, 'update'])->middleware(ApiAuthMiddleware::class);
        Route::put('booking', [BookingController::class, 'update'])->middleware(ApiAuthMiddleware::class);
        Route::put('lessor', [LessorController::class, 'update'])->middleware(ApiAuthMiddleware::class);
    }
);
