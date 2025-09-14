<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Sample data - you'll replace with actual booking data
        $bookingStats = [
            'total_bookings' => 0,
            'upcoming_bookings' => 0,
            'completed_bookings' => 0,
            'cancelled_bookings' => 0,
        ];
        
        return view('customer.dashboard', compact('user', 'bookingStats'));
    }

    /**
     * Display customer bookings
     */
    public function bookings()
    {
        $user = Auth::user();
        // Here you would load the user's bookings
        
        return view('customer.bookings.index', compact('user'));
    }

    /**
     * Display customer profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('customer.profile.show', compact('user'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
        ]);

        $user->update($request->only([
            'name', 'phone', 'address', 'city', 'country', 'date_of_birth'
        ]));

        return back()->with('success', 'Profile updated successfully.');
    }
}
