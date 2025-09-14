<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
    });
});

// Public hotel and room routes
Route::get('hotels', [HotelController::class, 'index']);
Route::get('hotels/{id}', [HotelController::class, 'show']);
Route::get('rooms', [RoomController::class, 'index']); // Requires hotel_id parameter
Route::get('rooms/{id}', [RoomController::class, 'show']);
Route::get('rooms/{id}/availability', [RoomController::class, 'checkAvailability']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Hotel management routes
    Route::apiResource('hotels', HotelController::class)->except(['index', 'show']);
    Route::get('my-hotels', [HotelController::class, 'myHotels']);
    
    // Room management routes
    Route::apiResource('rooms', RoomController::class)->except(['index', 'show']);
    
    // Booking routes
    Route::apiResource('bookings', BookingController::class);
    Route::get('hotel-bookings', [BookingController::class, 'hotelBookings']);
    
    // User profile route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
