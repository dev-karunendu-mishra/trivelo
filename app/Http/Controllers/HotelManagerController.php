<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:hotel_manager');
    }

    /**
     * Display hotel manager dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Sample data - you'll replace with actual hotel data
        $hotelStats = [
            'total_rooms' => 0,
            'available_rooms' => 0,
            'bookings_today' => 0,
            'total_revenue' => 0,
        ];
        
        return view('hotel-manager.dashboard', compact('user', 'hotelStats'));
    }

    /**
     * Display hotel profile
     */
    public function hotel()
    {
        $user = Auth::user();
        // Here you would load the user's hotel data
        
        return view('hotel-manager.hotel.show', compact('user'));
    }

    /**
     * Display rooms management
     */
    public function rooms()
    {
        $user = Auth::user();
        // Here you would load the hotel's rooms
        
        return view('hotel-manager.rooms.index', compact('user'));
    }

    /**
     * Display bookings management
     */
    public function bookings()
    {
        $user = Auth::user();
        // Here you would load the hotel's bookings
        
        return view('hotel-manager.bookings.index', compact('user'));
    }
}
