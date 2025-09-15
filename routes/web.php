<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HotelManagerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BookingController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/hotels/{id}', [HomeController::class, 'hotelDetails'])->name('hotel.details');
Route::get('/api/destinations', [HomeController::class, 'getDestinations'])->name('api.destinations');

// Booking Routes
Route::middleware(['auth'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/availability', [BookingController::class, 'checkAvailability'])->name('availability');
    Route::get('/form', [BookingController::class, 'showBookingForm'])->name('form');
    Route::post('/submit', [BookingController::class, 'submitBooking'])->name('submit');
    Route::get('/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::post('/cancel/{booking}', [BookingController::class, 'cancel'])->name('cancel');
    Route::get('/details/{booking}', [BookingController::class, 'getBookingDetails'])->name('details');
});

Route::get('/showcase', function () {
    return view('showcase');
})->name('showcase');

// Theme Management Routes
Route::middleware(['web'])->prefix('themes')->name('theme.')->group(function () {
    Route::get('/', [ThemeController::class, 'index'])->name('index');
    Route::post('/switch', [ThemeController::class, 'switch'])->name('switch');
    Route::get('/current', [ThemeController::class, 'current'])->name('current');
    Route::get('/preview/{theme}', [ThemeController::class, 'preview'])->name('preview');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('super_admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('hotel_manager')) {
        return redirect()->route('hotel-manager.dashboard');
    } elseif ($user->hasRole('customer')) {
        return redirect()->route('customer.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/assign-role', [AdminController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::get('/roles-permissions', [AdminController::class, 'rolesPermissions'])->name('roles-permissions');
});

// Hotel Manager Routes
Route::middleware(['auth', 'role:hotel_manager'])->prefix('hotel-manager')->name('hotel-manager.')->group(function () {
    Route::get('/dashboard', [HotelManagerController::class, 'dashboard'])->name('dashboard');
    Route::get('/hotel', [HotelManagerController::class, 'hotel'])->name('hotel');
    Route::get('/rooms', [HotelManagerController::class, 'rooms'])->name('rooms');
    Route::get('/bookings', [HotelManagerController::class, 'bookings'])->name('bookings');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [CustomerController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [CustomerController::class, 'showBooking'])->name('booking.show');
    Route::post('/bookings/{booking}/cancel', [CustomerController::class, 'cancelBooking'])->name('booking.cancel');
    
    // Profile management
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/preferences', [CustomerController::class, 'updatePreferences'])->name('preferences.update');
    Route::put('/profile/password', [CustomerController::class, 'updatePassword'])->name('password.update');
    Route::post('/profile/picture', [CustomerController::class, 'updateProfilePicture'])->name('profile.picture');
    
    // Reviews
    Route::get('/reviews', [CustomerController::class, 'reviews'])->name('reviews');
    Route::get('/reviews/create/{booking}', [CustomerController::class, 'createReview'])->name('reviews.create');
    Route::post('/reviews/{booking}', [CustomerController::class, 'storeReview'])->name('reviews.store');
    
    // Wishlist/Favorites
    Route::get('/wishlist', [CustomerController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/add', [CustomerController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove', [CustomerController::class, 'removeFromWishlist'])->name('wishlist.remove');
    
    // Notifications
    Route::get('/notifications', [CustomerController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [CustomerController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [CustomerController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [CustomerController::class, 'deleteNotification'])->name('notifications.delete');
    Route::get('/notifications/count', [CustomerController::class, 'getUnreadNotificationsCount'])->name('notifications.count');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
