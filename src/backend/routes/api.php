<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LessorController;
use App\Http\Controllers\LodgingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdministratorApiAuthMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Middleware\CustomerApiAuthMiddleware;
use App\Http\Middleware\LodgingApiAuthMiddleware;

Route::prefix('v1')->group(function () {

    // Booking Routes
    Route::apiResource('/booking', BookingController::class, ['except' => ['create', 'edit', 'destroy']]); // Manage bookings except create, edit, and destroy
    Route::post('/booking', [BookingController::class, 'store'])->middleware(ApiAuthMiddleware::class); // Create a new booking
    Route::put('/booking', [BookingController::class, 'update'])->middleware(ApiAuthMiddleware::class); // Update a booking
    Route::delete('/booking', [BookingController::class, 'destroy'])->middleware(ApiAuthMiddleware::class); // Delete a booking
    Route::get('/booking/{customer_id}', [BookingController::class, 'show']); // Retrieve bookings by customer ID

    // Lodging Routes
    Route::apiResource('/lodging', LodgingController::class, ['except' => ['create', 'edit', 'destroy']]); // Manage lodgings except create, edit, and destroy
    Route::post('/lodging', [LodgingController::class, 'store'])->middleware(LodgingApiAuthMiddleware::class); // Create a new lodging
    Route::put('lodging', [LodgingController::class, 'update'])->middleware(ApiAuthMiddleware::class); // Update a lodging
    Route::delete('/lodging/{lodging_id}', [LodgingController::class, 'destroy'])->middleware(LodgingApiAuthMiddleware::class); // Delete a specific lodging
    Route::get('/lodging/{lodging_id}/booking', [LodgingController::class, 'indexBooking'])->middleware(LodgingApiAuthMiddleware::class); // Retrieve bookings for a specific lodging
    Route::get('/lodging/{lodging_id}/image', [LodgingController::class, 'getImage']); // Retrieve an image for a specific lodging
    Route::post('/lodging/{lodging_id}/image', [LodgingController::class, 'uploadImage'])->middleware(LodgingApiAuthMiddleware::class); // Upload an image for a specific lodging
    Route::delete('/lodging/{lodging_id}/image', [LodgingController::class, 'deleteImage'])->middleware(LodgingApiAuthMiddleware::class); // Delete an image for a specific lodging
    Route::get('/lessor/{lessor_id}/lodging', [LodgingController::class, 'indexLessorLodgings']); // Retrieve lodgings for a specific lessor

    // User Routes
    Route::get('/user', [UserController::class, 'index']); // Retrieve a list of all users
    Route::get('/user/{name}', [UserController::class, 'show']); // Retrieve a specific user by name
    Route::post('/user', [UserController::class, 'store']); // Create a new user
    Route::post('/user/login', [UserController::class, 'login']); // Log in a user
    Route::post('/user/{name}/logout', [UserController::class, 'logOut'])->middleware(ApiAuthMiddleware::class); // Log out a user
    Route::post('/user/{name}/password', [UserController::class, 'storePassword'])->middleware(ApiAuthMiddleware::class); // Store a new password for a user
    Route::post('/user/{name}/image', [UserController::class, 'uploadImage'])->middleware(ApiAuthMiddleware::class); // Upload an image for a specific user
    Route::get('/user/{name}/image', [UserController::class, 'getimage']); // Retrieve the image for a specific user
    Route::delete('/user/{name}', [UserController::class, 'destroy'])->middleware(AdministratorApiAuthMiddleware::class); // Delete a specific user
    Route::delete('/user/{name}/image', [UserController::class, 'deleteImage'])->middleware(ApiAuthMiddleware::class); // Delete an image for a specific user
    Route::patch('/user/{name}', [UserController::class, 'updatePartial'])->middleware(ApiAuthMiddleware::class); // Partially update a user's information
    Route::get('/user/identity', [UserController::class, 'getIdentity'])->middleware(ApiAuthMiddleware::class); // Get the identity of the currently authenticated user
    Route::get('/user_role', [UserController::class, 'indexUserRole']); // Retrieve a list of all user roles

    // Customer Routes
    Route::get('/customer', [CustomerController::class, 'index']); // Retrieve a list of all customers
    Route::get('/customer/{customer_id?}', [CustomerController::class, 'show']); // Retrieve a specific customer by ID
    Route::get('/customer/{customer_id}/booking', [CustomerController::class, 'indexBooking'])->middleware(CustomerApiAuthMiddleware::class); // Retrieve bookings for a specific customer

    // Lessor Routes
    Route::get('/lessor', [LessorController::class, 'index']); // Retrieve a list of all lessors
    Route::get('/lessor/{lessor_id?}', [LessorController::class, 'show']); // Retrieve a specific lessor by ID

    // Booking Status Routes
    Route::get('/booking_status', [BookingController::class, 'indexBookingStatus']); // Retrieve a list of all booking statuses

    // Administrator Routes
    Route::get('/administrator', [UserController::class, 'indexAdministrator']); // Retrieve a list of all administrators
});
