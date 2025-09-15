<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HotelManagerController extends Controller
{
    public function __construct()
    {
        // Middleware is handled by the route group
    }

    /**
     * Display hotel manager dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $hotel = $user->hotels()->first(); // Assuming manager manages one hotel
        
        if (!$hotel) {
            return redirect()->route('hotel-manager.hotel.create')
                ->with('warning', 'Please set up your hotel profile first.');
        }
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats($hotel);
        $recentBookings = $this->getRecentBookings($hotel);
        $upcomingCheckins = $this->getUpcomingCheckins($hotel);
        $roomStatus = $this->getRoomStatus($hotel);
        $revenueData = $this->getRevenueData($hotel);
        
        // Header stats for layout
        $headerStats = [
            'occupancy' => $stats['occupancy_rate'] . '%',
            'available_rooms' => $stats['available_rooms'],
            'pending_checkins' => $upcomingCheckins->count(),
        ];
        
        return view('hotel-manager.dashboard.index', compact(
            'user', 'hotel', 'stats', 'recentBookings', 'upcomingCheckins', 
            'roomStatus', 'revenueData', 'headerStats'
        ));
    }

    /**
     * Display hotel profile
     */
    public function hotel()
    {
        $user = Auth::user();
        $hotel = $user->hotels()->first();
        
        if (!$hotel) {
            return redirect()->route('hotel-manager.hotel.create');
        }
        
        return view('hotel-manager.hotel.show', compact('user', 'hotel'));
    }

    /**
     * Display rooms management
     */
    public function rooms()
    {
        $user = Auth::user();
        $hotel = $user->hotels()->first();
        
        if (!$hotel) {
            return redirect()->route('hotel-manager.hotel.create');
        }
        
        $rooms = $hotel->rooms()->with(['bookings' => function($query) {
            $query->where('check_in', '<=', Carbon::today())
                  ->where('check_out', '>=', Carbon::today())
                  ->where('status', '!=', 'cancelled');
        }])->paginate(20);
        
        $roomStats = [
            'total_rooms' => $hotel->rooms()->count(),
            'available_rooms' => $hotel->rooms()->available()->count(),
            'occupied_rooms' => $hotel->rooms()->occupied()->count(),
            'maintenance_rooms' => $hotel->rooms()->where('status', 'maintenance')->count(),
        ];
        
        return view('hotel-manager.rooms.index', compact('user', 'hotel', 'rooms', 'roomStats'));
    }

    /**
     * Display bookings management
     */
    public function bookings()
    {
        $user = Auth::user();
        $hotel = $user->hotels()->first();
        
        if (!$hotel) {
            return redirect()->route('hotel-manager.hotel.create');
        }
        
        $bookings = Booking::whereHas('room', function($query) use ($hotel) {
                $query->where('hotel_id', $hotel->id);
            })
            ->with(['user', 'room', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $bookingStats = $this->getBookingStats($hotel);
        
        return view('hotel-manager.bookings.index', compact('user', 'hotel', 'bookings', 'bookingStats'));
    }

    /**
     * Display analytics dashboard
     */
    public function analytics()
    {
        $user = Auth::user();
        $hotel = $user->hotels()->first();
        
        if (!$hotel) {
            return redirect()->route('hotel-manager.hotel.create');
        }
        
        $analytics = $this->getAnalyticsData($hotel);
        
        return view('hotel-manager.analytics.index', compact('user', 'hotel', 'analytics'));
    }

    /**
     * Display guest management
     */
    public function guests()
    {
        $user = Auth::user();
        $hotel = $user->hotels()->first();
        
        if (!$hotel) {
            return redirect()->route('hotel-manager.hotel.create');
        }
        
        $guests = User::whereHas('bookings', function($query) use ($hotel) {
                $query->whereHas('room', function($q) use ($hotel) {
                    $q->where('hotel_id', $hotel->id);
                });
            })
            ->with(['bookings' => function($query) use ($hotel) {
                $query->whereHas('room', function($q) use ($hotel) {
                    $q->where('hotel_id', $hotel->id);
                })->latest();
            }])
            ->paginate(20);
        
        return view('hotel-manager.guests.index', compact('user', 'hotel', 'guests'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats($hotel)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $totalRooms = $hotel->rooms()->count();
        $occupiedRooms = $hotel->rooms()->whereHas('bookings', function($query) use ($today) {
            $query->where('check_in', '<=', $today)
                  ->where('check_out', '>', $today)
                  ->where('status', '!=', 'cancelled');
        })->count();
        
        $availableRooms = $totalRooms - $occupiedRooms;
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        
        $bookingsToday = Booking::whereHas('room', function($query) use ($hotel) {
                $query->where('hotel_id', $hotel->id);
            })
            ->whereDate('created_at', $today)
            ->count();
        
        $thisMonthRevenue = Payment::whereHas('booking', function($query) use ($hotel) {
                $query->whereHas('room', function($q) use ($hotel) {
                    $q->where('hotel_id', $hotel->id);
                });
            })
            ->where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount');
        
        $lastMonthRevenue = Payment::whereHas('booking', function($query) use ($hotel) {
                $query->whereHas('room', function($q) use ($hotel) {
                    $q->where('hotel_id', $hotel->id);
                });
            })
            ->where('status', 'completed')
            ->whereBetween('created_at', [$lastMonth, $thisMonth])
            ->sum('amount');
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
            : 0;
        
        return [
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'available_rooms' => $availableRooms,
            'occupancy_rate' => $occupancyRate,
            'bookings_today' => $bookingsToday,
            'monthly_revenue' => $thisMonthRevenue,
            'revenue_growth' => $revenueGrowth,
        ];
    }

    /**
     * Get recent bookings
     */
    private function getRecentBookings($hotel)
    {
        return Booking::whereHas('room', function($query) use ($hotel) {
                $query->where('hotel_id', $hotel->id);
            })
            ->with(['user', 'room'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get upcoming check-ins
     */
    private function getUpcomingCheckins($hotel)
    {
        return Booking::whereHas('room', function($query) use ($hotel) {
                $query->where('hotel_id', $hotel->id);
            })
            ->where('check_in', Carbon::today())
            ->where('status', 'confirmed')
            ->with(['user', 'room'])
            ->get();
    }

    /**
     * Get room status summary
     */
    private function getRoomStatus($hotel)
    {
        return [
            'available' => $hotel->rooms()->available()->count(),
            'occupied' => $hotel->rooms()->occupied()->count(),
            'maintenance' => $hotel->rooms()->where('status', 'maintenance')->count(),
            'out_of_order' => $hotel->rooms()->where('status', 'out_of_order')->count(),
        ];
    }

    /**
     * Get revenue data for charts
     */
    private function getRevenueData($hotel)
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Payment::whereHas('booking', function($query) use ($hotel) {
                    $query->whereHas('room', function($q) use ($hotel) {
                        $q->where('hotel_id', $hotel->id);
                    });
                })
                ->where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('amount');
            
            $data[] = [
                'date' => $date->format('M j'),
                'revenue' => $revenue,
            ];
        }
        
        return $data;
    }

    /**
     * Get booking statistics
     */
    private function getBookingStats($hotel)
    {
        $total = Booking::whereHas('room', function($query) use ($hotel) {
            $query->where('hotel_id', $hotel->id);
        })->count();
        
        $confirmed = Booking::whereHas('room', function($query) use ($hotel) {
            $query->where('hotel_id', $hotel->id);
        })->where('status', 'confirmed')->count();
        
        $pending = Booking::whereHas('room', function($query) use ($hotel) {
            $query->where('hotel_id', $hotel->id);
        })->where('status', 'pending')->count();
        
        $cancelled = Booking::whereHas('room', function($query) use ($hotel) {
            $query->where('hotel_id', $hotel->id);
        })->where('status', 'cancelled')->count();
        
        return compact('total', 'confirmed', 'pending', 'cancelled');
    }

    /**
     * Get analytics data
     */
    private function getAnalyticsData($hotel)
    {
        // This would contain comprehensive analytics data
        return [
            'monthly_revenue' => $this->getMonthlyRevenue($hotel),
            'occupancy_trends' => $this->getOccupancyTrends($hotel),
            'guest_demographics' => $this->getGuestDemographics($hotel),
            'booking_sources' => $this->getBookingSources($hotel),
        ];
    }

    /**
     * Get monthly revenue data
     */
    private function getMonthlyRevenue($hotel)
    {
        // Implementation for monthly revenue analytics
        return [];
    }

    /**
     * Get occupancy trends
     */
    private function getOccupancyTrends($hotel)
    {
        // Implementation for occupancy trends
        return [];
    }

    /**
     * Get guest demographics
     */
    private function getGuestDemographics($hotel)
    {
        // Implementation for guest demographics
        return [];
    }

    /**
     * Get booking sources
     */
    private function getBookingSources($hotel)
    {
        // Implementation for booking sources analytics
        return [];
    }
}
