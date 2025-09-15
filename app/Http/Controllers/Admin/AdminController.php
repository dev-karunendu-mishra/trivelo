<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is handled in routes
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Get key metrics
        $metrics = $this->getDashboardMetrics();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        return view('admin.dashboard.index', compact('metrics', 'recentActivities'));
    }

    /**
     * Get dashboard metrics.
     */
    private function getDashboardMetrics()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Total bookings
        $totalBookings = Booking::count();
        $lastMonthBookings = Booking::where('created_at', '>=', $lastMonth)
                                  ->where('created_at', '<', $currentMonth)
                                  ->count();
        $currentMonthBookings = Booking::where('created_at', '>=', $currentMonth)->count();
        $bookingsGrowth = $lastMonthBookings > 0 
            ? (($currentMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100 
            : 0;

        // Total revenue
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $lastMonthRevenue = Payment::where('status', 'completed')
                                  ->where('created_at', '>=', $lastMonth)
                                  ->where('created_at', '<', $currentMonth)
                                  ->sum('amount');
        $currentMonthRevenue = Payment::where('status', 'completed')
                                     ->where('created_at', '>=', $currentMonth)
                                     ->sum('amount');
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        // Occupancy rate (simplified calculation)
        $totalRooms = DB::table('rooms')->count();
        $occupiedRooms = Booking::where('check_in_date', '<=', Carbon::now())
                               ->where('check_out_date', '>=', Carbon::now())
                               ->where('status', 'confirmed')
                               ->count();
        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        // Active users (users who logged in within last 30 days)
        $activeUsers = User::where('last_login', '>=', Carbon::now()->subDays(30))->count();
        $lastMonthActiveUsers = User::where('last_login', '>=', $lastMonth)
                                   ->where('last_login', '<', $currentMonth)
                                   ->count();
        $currentMonthActiveUsers = User::where('last_login', '>=', $currentMonth)->count();
        $activeUsersGrowth = $lastMonthActiveUsers > 0 
            ? (($currentMonthActiveUsers - $lastMonthActiveUsers) / $lastMonthActiveUsers) * 100 
            : 0;

        return [
            'total_bookings' => number_format($totalBookings),
            'bookings_growth' => round($bookingsGrowth, 1),
            'total_revenue' => $totalRevenue,
            'revenue_growth' => round($revenueGrowth, 1),
            'occupancy_rate' => round($occupancyRate, 1),
            'active_users' => number_format($activeUsers),
            'active_users_growth' => round($activeUsersGrowth, 1),
        ];
    }

    /**
     * Get recent activities for dashboard.
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'hotel'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        foreach ($recentBookings as $booking) {
            $activities->push([
                'type' => 'booking',
                'icon' => 'bi-calendar-plus',
                'color' => 'success',
                'title' => 'New Booking',
                'user' => $booking->user,
                'hotel' => $booking->hotel->name ?? 'Unknown Hotel',
                'amount' => $booking->total_amount ?? 0,
                'status' => ucfirst($booking->status),
                'status_class' => $this->getStatusClass($booking->status),
                'time' => $booking->created_at->diffForHumans(),
            ]);
        }

        // Recent users
        $recentUsers = User::where('role', 'customer')
                          ->orderBy('created_at', 'desc')
                          ->limit(3)
                          ->get();

        foreach ($recentUsers as $user) {
            $activities->push([
                'type' => 'user',
                'icon' => 'bi-person-plus',
                'color' => 'info',
                'title' => 'User Registration',
                'user' => $user,
                'hotel' => '-',
                'amount' => 0,
                'status' => 'Active',
                'status_class' => 'bg-info',
                'time' => $user->created_at->diffForHumans(),
            ]);
        }

        // Recent payments
        $recentPayments = Payment::with(['booking.user', 'booking.hotel'])
                                ->where('status', 'completed')
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();

        foreach ($recentPayments as $payment) {
            $activities->push([
                'type' => 'payment',
                'icon' => 'bi-credit-card',
                'color' => 'primary',
                'title' => 'Payment Received',
                'user' => $payment->booking->user ?? null,
                'hotel' => $payment->booking->hotel->name ?? 'Unknown Hotel',
                'amount' => $payment->amount,
                'status' => 'Paid',
                'status_class' => 'bg-success',
                'time' => $payment->created_at->diffForHumans(),
            ]);
        }

        return $activities->sortByDesc('time')->take(10);
    }

    /**
     * Get status CSS class.
     */
    private function getStatusClass($status)
    {
        return match($status) {
            'pending' => 'bg-warning',
            'confirmed' => 'bg-success',
            'cancelled' => 'bg-danger',
            'completed' => 'bg-info',
            default => 'bg-secondary',
        };
    }

    /**
     * Get chart data for bookings.
     */
    public function getBookingChartData(Request $request)
    {
        $period = $request->get('period', '7d');
        
        $data = match($period) {
            '7d' => $this->getBookingDataFor7Days(),
            '30d' => $this->getBookingDataFor30Days(),
            '90d' => $this->getBookingDataFor90Days(),
            default => $this->getBookingDataFor7Days(),
        };

        return response()->json($data);
    }

    /**
     * Get booking data for 7 days.
     */
    private function getBookingDataFor7Days()
    {
        $labels = [];
        $bookingData = [];
        $revenueData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M j');
            
            $bookings = Booking::whereDate('created_at', $date)->count();
            $revenue = Payment::whereDate('created_at', $date)
                             ->where('status', 'completed')
                             ->sum('amount');
            
            $bookingData[] = $bookings;
            $revenueData[] = $revenue;
        }

        return [
            'labels' => $labels,
            'bookings' => $bookingData,
            'revenue' => $revenueData,
        ];
    }

    /**
     * Get booking data for 30 days.
     */
    private function getBookingDataFor30Days()
    {
        $labels = [];
        $bookingData = [];
        $revenueData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M j');
            
            $bookings = Booking::whereDate('created_at', $date)->count();
            $revenue = Payment::whereDate('created_at', $date)
                             ->where('status', 'completed')
                             ->sum('amount');
            
            $bookingData[] = $bookings;
            $revenueData[] = $revenue;
        }

        return [
            'labels' => $labels,
            'bookings' => $bookingData,
            'revenue' => $revenueData,
        ];
    }

    /**
     * Get booking data for 90 days.
     */
    private function getBookingDataFor90Days()
    {
        $labels = [];
        $bookingData = [];
        $revenueData = [];

        for ($i = 89; $i >= 0; $i -= 7) {
            $endDate = Carbon::now()->subDays($i);
            $startDate = $endDate->copy()->subDays(6);
            $labels[] = $startDate->format('M j') . ' - ' . $endDate->format('M j');
            
            $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
            $revenue = Payment::whereBetween('created_at', [$startDate, $endDate])
                             ->where('status', 'completed')
                             ->sum('amount');
            
            $bookingData[] = $bookings;
            $revenueData[] = $revenue;
        }

        return [
            'labels' => array_reverse($labels),
            'bookings' => array_reverse($bookingData),
            'revenue' => array_reverse($revenueData),
        ];
    }

    /**
     * Get revenue distribution data.
     */
    public function getRevenueDistribution()
    {
        $distribution = Hotel::select('type', DB::raw('SUM(payments.amount) as total_revenue'))
                            ->join('bookings', 'hotels.id', '=', 'bookings.hotel_id')
                            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
                            ->where('payments.status', 'completed')
                            ->groupBy('type')
                            ->get();

        $totalRevenue = $distribution->sum('total_revenue');
        
        $data = [
            'labels' => $distribution->pluck('type')->map(fn($type) => ucfirst($type))->toArray(),
            'data' => $distribution->map(function($item) use ($totalRevenue) {
                return $totalRevenue > 0 ? round(($item->total_revenue / $totalRevenue) * 100, 1) : 0;
            })->toArray(),
        ];

        return response()->json($data);
    }
}