<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LessorController;
use App\Http\Controllers\LodgingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Middleware\CustomerApiAuthMiddleware;
use App\Http\Middleware\LodgingApiAuthMiddleware;


Route::prefix('v1')->group(
    function () {
        Route::apiResource('/booking', BookingController::class, ['except' => ['create', 'edit']]);
        Route::apiResource('/lodging', LodgingController::class, ['except' => ['create', 'edit']]);
        
        Route::get('/booking_status', [BookingController::class, 'indexBookingStatus']);
        Route::get('/customer/{customer_id?}', [CustomerController::class, 'show']);
        Route::get('/customer/{customer_id}/booking', [CustomerController::class, 'indexBooking'])->middleware(CustomerApiAuthMiddleware::class);
        Route::get('/lessor/{lessor_id?}', [LessorController::class, 'show']);
        Route::get('/lodging/{lodging_id}/booking', [LodgingController::class, 'indexBooking'])->middleware(LodgingApiAuthMiddleware::class);

        Route::get('/user/getidentity', [UserController::class, 'getIdentity'])->middleware(ApiAuthMiddleware::class);
        Route::get('/user', [UserController::class, 'index']);
        Route::get('/user_role', [UserController::class, 'indexUserRole']);
        Route::get('/lodging/getimage/{filename}', [LodgingController::class,'getImage']);
        Route::get('/user/getimage/{filename}', [UserController::class, 'getimage']);

        Route::post('/user/{name}/uploadimage', [UserController::class, 'uploadImage'])->middleware(ApiAuthMiddleware::class);
        Route::post('/lodging/{lodging_id}/uploadimage', [LodgingController::class, 'uploadImage'])->middleware(LodgingApiAuthMiddleware::class);
        Route::post('/user', [UserController::class, 'store']);
        Route::post('/user/login', [UserController::class, 'login']);
        Route::post('/user/{name}/logout', [UserController::class, 'logOut'])->middleware(ApiAuthMiddleware::class);
        Route::post('/user/{name}/password', [UserController::class, 'storePassword'])->middleware(ApiAuthMiddleware::class);
        Route::post('/lodging', [LodgingController::class, 'store'])->middleware(LodgingApiAuthMiddleware::class);
        Route::post('/lessor', [LessorController::class, 'store'])->middleware(ApiAuthMiddleware::class);
        Route::post('/booking', [BookingController::class, 'store'])->middleware(ApiAuthMiddleware::class);

        Route::delete('/booking', [BookingController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/lodging', [LodgingController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/user/{name}', [UserController::class,'destroy'])->middleware(ApiAuthMiddleware::class);

        Route::patch('/user/{name}', [UserController::class,'updatePartial'])->middleware(ApiAuthMiddleware::class);
        Route::put('lodging', [LodgingController::class, 'update'])->middleware(ApiAuthMiddleware::class);
        Route::put('booking', [BookingController::class, 'update'])->middleware(ApiAuthMiddleware::class);
        Route::put('lessor', [LessorController::class, 'update'])->middleware(ApiAuthMiddleware::class);
    }
);
