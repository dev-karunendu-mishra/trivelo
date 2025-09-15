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
Route::get('/api/search', [HomeController::class, 'searchAjax'])->name('api.search');

// Booking Routes
Route::middleware(['auth'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/availability', [BookingController::class, 'checkAvailability'])->name('availability');
    Route::get('/form', [BookingController::class, 'showBookingForm'])->name('form');
    Route::post('/submit', [BookingController::class, 'submitBooking'])->name('submit');
    Route::get('/payment/{booking}', [BookingController::class, 'showPaymentForm'])->name('payment');
    Route::get('/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::post('/cancel/{booking}', [BookingController::class, 'cancel'])->name('cancel');
    Route::get('/details/{booking}', [BookingController::class, 'getBookingDetails'])->name('details');
});

// Payment API Routes
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::post('/bookings/{booking}/payment-intent', [App\Http\Controllers\Frontend\PaymentController::class, 'createPaymentIntent'])->name('bookings.payment-intent');
    Route::post('/payments/confirm', [App\Http\Controllers\Frontend\PaymentController::class, 'confirmPayment'])->name('payments.confirm');
    Route::post('/payments/failed', [App\Http\Controllers\Frontend\PaymentController::class, 'paymentFailed'])->name('payments.failed');
    Route::get('/bookings/{booking}/payment-status', [App\Http\Controllers\Frontend\PaymentController::class, 'getPaymentStatus'])->name('bookings.payment-status');
});

// Stripe Webhook (no auth middleware)
Route::post('/stripe/webhook', [App\Http\Controllers\Frontend\PaymentController::class, 'handleWebhook'])->name('stripe.webhook');

// Admin Payment Management Routes
Route::middleware(['auth', 'role:super_admin|hotel_manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/bookings/{booking}/refund', [App\Http\Controllers\Frontend\PaymentController::class, 'processRefund'])->name('bookings.refund');
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

// Simple test route for debugging auth and roles
Route::get('/test-auth', function () {
    if (auth()->guest()) {
        return 'Not logged in. <a href="/login">Login here</a>';
    }
    
    $user = auth()->user();
    return [
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role_field' => $user->role,
            'spatie_roles' => $user->getRoleNames()->toArray(),
        ],
        'can_access_customer' => $user->role === 'customer' || $user->getRoleNames()->contains('customer'),
        'can_access_admin' => $user->role === 'admin' || $user->getRoleNames()->contains('admin'),
        'can_access_hotel_manager' => $user->role === 'hotel_manager' || $user->getRoleNames()->contains('hotel_manager'),
    ];
});

Route::get('/debug-roles', function () {
    $user = auth()->user();
    if (!$user) {
        return 'Not logged in';
    }
    
    return [
        'id' => $user->id,
        'name' => $user->name,
        'role_field' => $user->role,
        'spatie_roles' => $user->getRoleNames()->toArray(),
        'is_customer' => $user->isCustomer(),
        'is_hotel_manager' => $user->isHotelManager(),
        'is_super_admin' => $user->isSuperAdmin(),
    ];
})->middleware('auth');

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Debug: Log user information
    \Log::info('Dashboard access attempt', [
        'user_id' => $user->id,
        'user_name' => $user->name,
        'role_field' => $user->role,
        'spatie_roles' => $user->getRoleNames()->toArray()
    ]);
    
    if ($user->role === 'admin' || $user->getRoleNames()->contains('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'hotel_manager' || $user->getRoleNames()->contains('hotel_manager')) {
        return redirect()->route('hotel-manager.dashboard');
    } elseif ($user->role === 'customer' || $user->getRoleNames()->contains('customer')) {
        return redirect()->route('customer.dashboard');
    }
    
    // Fallback: Create customer dashboard view if none of the above match
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Super Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    Route::get('/chart-data/bookings', [App\Http\Controllers\Admin\AdminController::class, 'getBookingChartData'])->name('chart.bookings');
    Route::get('/chart-data/revenue-distribution', [App\Http\Controllers\Admin\AdminController::class, 'getRevenueDistribution'])->name('chart.revenue');
    
    // Analytics & Reports
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.dashboard');
    Route::get('/analytics/api/metrics', [App\Http\Controllers\Admin\AnalyticsController::class, 'getMetrics'])->name('analytics.api.metrics');
    Route::get('/analytics/api/trends', [App\Http\Controllers\Admin\AnalyticsController::class, 'getTrends'])->name('analytics.api.trends');
    Route::get('/analytics/api/comparative', [App\Http\Controllers\Admin\AnalyticsController::class, 'getComparativeAnalytics'])->name('analytics.api.comparative');
    Route::post('/analytics/generate-report', [App\Http\Controllers\Admin\AnalyticsController::class, 'generateReport'])->name('analytics.generate-report');
    
    // Users management
    Route::get('/users', function() {
        return view('admin.users.index');
    })->name('users');
    
    // Hotels management
    Route::get('/hotels', function() {
        return view('admin.hotels.index');
    })->name('hotels');
    
    // Bookings management
    Route::get('/bookings', function() {
        return view('admin.bookings.index');
    })->name('bookings');
    
    // Reports
    Route::get('/reports', function() {
        return view('admin.reports.index');
    })->name('reports');
});

// Hotel Manager Routes
Route::middleware(['auth', 'hotel_manager'])->prefix('hotel-manager')->name('hotel-manager.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HotelManagerController::class, 'dashboard'])->name('dashboard');
    
    // Hotel Management
    Route::get('/hotel', [HotelManagerController::class, 'hotel'])->name('hotel');
    Route::get('/hotel/create', function() { return view('hotel-manager.hotel.create'); })->name('hotel.create');
    Route::post('/hotel', function() { 
        // TODO: Add hotel creation logic
        return redirect()->route('hotel-manager.hotel')->with('success', 'Hotel created successfully!'); 
    })->name('hotel.store');
    Route::get('/hotel/edit', function() { return view('hotel-manager.hotel.edit'); })->name('hotel.edit');
    Route::put('/hotel', function() { 
        // TODO: Add hotel update logic
        return redirect()->route('hotel-manager.hotel')->with('success', 'Hotel updated successfully!'); 
    })->name('hotel.update');
    
    // Room Management
    Route::get('/rooms', [HotelManagerController::class, 'rooms'])->name('rooms');
    Route::get('/rooms/create', function() { return view('hotel-manager.rooms.create'); })->name('rooms.create');
    Route::post('/rooms', function() { 
        // TODO: Add room creation logic
        return redirect()->route('hotel-manager.rooms')->with('success', 'Room created successfully!'); 
    })->name('rooms.store');
    
    // Booking Management
    Route::get('/bookings', [HotelManagerController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/manual', function() { return view('hotel-manager.bookings.manual'); })->name('bookings.manual');
    Route::post('/bookings', function() { 
        // TODO: Add booking creation logic
        return redirect()->route('hotel-manager.bookings')->with('success', 'Booking created successfully!'); 
    })->name('bookings.store');
    
    // Analytics & Reports
    Route::get('/analytics', [HotelManagerController::class, 'analytics'])->name('analytics.index');
    Route::get('/analytics/api/metrics', [App\Http\Controllers\HotelManager\AnalyticsController::class, 'getMetrics'])->name('analytics.api.metrics');
    Route::get('/analytics/api/trends', [App\Http\Controllers\HotelManager\AnalyticsController::class, 'getTrends'])->name('analytics.api.trends');
    Route::get('/analytics/api/comparative', [App\Http\Controllers\HotelManager\AnalyticsController::class, 'getComparativeAnalytics'])->name('analytics.api.comparative');
    Route::get('/analytics/room-performance', [App\Http\Controllers\HotelManager\AnalyticsController::class, 'getRoomPerformance'])->name('analytics.room-performance');
    Route::post('/analytics/generate-report', [App\Http\Controllers\HotelManager\AnalyticsController::class, 'generateReport'])->name('analytics.generate-report');
    
    // Guest Management
    Route::get('/guests', [HotelManagerController::class, 'guests'])->name('guests');
    Route::get('/guests/checkin', function() { return view('hotel-manager.guests.checkin'); })->name('guests.checkin');
    Route::post('/guests/checkin', function() { 
        // TODO: Add guest check-in logic
        return redirect()->route('hotel-manager.guests')->with('success', 'Guest checked in successfully!'); 
    })->name('guests.checkin.process');
    Route::get('/guests/checkout', function() { return view('hotel-manager.guests.checkout'); })->name('guests.checkout');
    Route::post('/guests/checkout', function() { 
        // TODO: Add guest check-out logic
        return redirect()->route('hotel-manager.guests')->with('success', 'Guest checked out successfully!'); 
    })->name('guests.checkout.process');
    
    // Reviews Management
    Route::get('/reviews', function() { return view('hotel-manager.reviews.index'); })->name('reviews');
    
    // Calendar
    Route::get('/calendar', function() { return view('hotel-manager.calendar.index'); })->name('calendar');
    
    // Communications & Maintenance
    Route::get('/communications', function() { return view('hotel-manager.communications.index'); })->name('communications');
    Route::post('/communications/send', function() { 
        // TODO: Add communication sending logic
        return redirect()->back()->with('success', 'Message sent successfully!'); 
    })->name('communications.send');
    Route::get('/maintenance', function() { return view('hotel-manager.maintenance.index'); })->name('maintenance');
    Route::post('/maintenance', function() { 
        // TODO: Add maintenance request logic
        return redirect()->route('hotel-manager.maintenance')->with('success', 'Maintenance request submitted successfully!'); 
    })->name('maintenance.store');
    Route::get('/maintenance/report', function() { return view('hotel-manager.maintenance.report'); })->name('maintenance.report');
    
    // Settings & Profile
    Route::get('/settings', function() { return view('hotel-manager.settings.index'); })->name('settings');
    Route::post('/settings/{section}', function($section) { 
        // TODO: Add settings update logic for section: $section
        return redirect()->route('hotel-manager.settings')->with('success', 'Settings updated successfully!'); 
    })->name('settings.update');
    Route::get('/profile', function() { return view('hotel-manager.profile.index'); })->name('profile');
    Route::post('/profile', function() { 
        // TODO: Add profile update logic
        return redirect()->route('hotel-manager.profile')->with('success', 'Profile updated successfully!'); 
    })->name('profile.update');
    Route::post('/profile/password', function() { 
        // TODO: Add password change logic
        return redirect()->route('hotel-manager.profile')->with('success', 'Password changed successfully!'); 
    })->name('profile.password');
    Route::get('/help', function() { return view('hotel-manager.help.index'); })->name('help');
    Route::post('/support/contact', function() { 
        // TODO: Add support contact logic
        return redirect()->route('hotel-manager.help')->with('success', 'Support request submitted successfully!'); 
    })->name('support.contact');
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
