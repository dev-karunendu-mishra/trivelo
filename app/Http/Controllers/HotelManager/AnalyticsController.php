<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display hotel analytics dashboard
     */
    public function dashboard(Request $request)
    {
        $hotels = $this->getManagerHotels();
        $selectedHotel = $request->get('hotel_id', $hotels->first()?->id);
        
        $filters = $this->getFilters($request);
        $filters['hotel_id'] = $selectedHotel;
        
        $analytics = [
            'revenue' => $this->analyticsService->getRevenueAnalytics($filters),
            'bookings' => $this->analyticsService->getBookingAnalytics($filters),
            'occupancy' => $this->analyticsService->getOccupancyAnalytics($filters),
            'customers' => $this->analyticsService->getCustomerAnalytics($filters),
        ];

        return view('hotel-manager.analytics.dashboard', compact('analytics', 'filters', 'hotels', 'selectedHotel'));
    }

    /**
     * Get hotel performance metrics
     */
    public function hotelPerformance(Request $request): JsonResponse
    {
        $hotelId = $request->get('hotel_id');
        
        if (!$this->canAccessHotel($hotelId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filters = $this->getFilters($request);
        $filters['hotel_id'] = $hotelId;

        $performance = $this->analyticsService->getPerformanceMetrics($filters);

        return response()->json([
            'success' => true,
            'data' => $performance
        ]);
    }

    /**
     * Get room performance analytics
     */
    public function roomPerformance(Request $request): JsonResponse
    {
        $hotelId = $request->get('hotel_id');
        
        if (!$this->canAccessHotel($hotelId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $hotel = Hotel::with(['rooms.bookings' => function($query) use ($request) {
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('check_in_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            }
            $query->where('status', 'confirmed');
        }])->findOrFail($hotelId);

        $roomPerformance = [];
        
        foreach ($hotel->rooms as $room) {
            $bookings = $room->bookings;
            $totalRevenue = $bookings->sum('total_amount');
            $totalBookings = $bookings->count();
            $totalNights = $bookings->sum('nights');

            $roomPerformance[] = [
                'room_id' => $room->id,
                'room_number' => $room->room_number,
                'room_type' => $room->type,
                'total_revenue' => $totalRevenue,
                'total_bookings' => $totalBookings,
                'total_nights' => $totalNights,
                'average_rate' => $totalNights > 0 ? round($totalRevenue / $totalNights, 2) : 0,
                'occupancy_rate' => $this->calculateRoomOccupancy($room, $request),
            ];
        }

        // Sort by revenue descending
        usort($roomPerformance, function($a, $b) {
            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        return response()->json([
            'success' => true,
            'data' => $roomPerformance
        ]);
    }

    /**
     * Get revenue trends for hotel
     */
    public function revenueTrends(Request $request): JsonResponse
    {
        $hotelId = $request->get('hotel_id');
        
        if (!$this->canAccessHotel($hotelId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filters = $this->getFilters($request);
        $filters['hotel_id'] = $hotelId;

        $analytics = $this->analyticsService->getRevenueAnalytics($filters);

        return response()->json([
            'success' => true,
            'data' => [
                'monthly_revenue' => $analytics['monthly_revenue'],
                'revenue_trends' => $analytics['revenue_trends'],
                'total_revenue' => $analytics['total_revenue'],
                'average_booking_value' => $analytics['average_booking_value'],
            ]
        ]);
    }

    /**
     * Get booking trends for hotel
     */
    public function bookingTrends(Request $request): JsonResponse
    {
        $hotelId = $request->get('hotel_id');
        
        if (!$this->canAccessHotel($hotelId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filters = $this->getFilters($request);
        $filters['hotel_id'] = $hotelId;

        $analytics = $this->analyticsService->getBookingAnalytics($filters);

        return response()->json([
            'success' => true,
            'data' => [
                'booking_trends' => $analytics['booking_trends'],
                'seasonal_patterns' => $analytics['seasonal_patterns'],
                'cancellation_rate' => $analytics['cancellation_rate'],
                'average_stay_duration' => $analytics['average_stay_duration'],
                'popular_room_types' => $analytics['popular_room_types'],
            ]
        ]);
    }

    /**
     * Generate hotel-specific report
     */
    public function generateHotelReport(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'report_type' => 'required|in:revenue,bookings,occupancy,comprehensive',
            'format' => 'required|in:pdf,excel,csv',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if (!$this->canAccessHotel($request->hotel_id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filters = $this->getFilters($request);
        $filters['hotel_id'] = $request->hotel_id;

        switch ($request->report_type) {
            case 'revenue':
                $data = $this->analyticsService->getRevenueAnalytics($filters);
                break;
            case 'bookings':
                $data = $this->analyticsService->getBookingAnalytics($filters);
                break;
            case 'occupancy':
                $data = $this->analyticsService->getOccupancyAnalytics($filters);
                break;
            case 'comprehensive':
            default:
                $data = $this->analyticsService->getPerformanceMetrics($filters);
                break;
        }

        return $this->exportReport($data, $request->format, $request->report_type, $request->hotel_id);
    }

    /**
     * Get hotels managed by current user
     */
    private function getManagerHotels()
    {
        $user = Auth::user();
        
        // If super admin, return all hotels
        if ($user->hasRole('super-admin')) {
            return Hotel::all();
        }
        
        // Return hotels managed by this hotel manager
        return $user->managedHotels ?? collect();
    }

    /**
     * Check if current user can access hotel data
     */
    private function canAccessHotel($hotelId): bool
    {
        if (!$hotelId) {
            return false;
        }

        $user = Auth::user();
        
        // Super admin can access all hotels
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Check if user manages this hotel
        return $this->getManagerHotels()->contains('id', $hotelId);
    }

    /**
     * Calculate room occupancy rate
     */
    private function calculateRoomOccupancy($room, Request $request): float
    {
        $startDate = $request->has('start_date') 
            ? \Carbon\Carbon::parse($request->start_date)
            : now()->subDays(30);
            
        $endDate = $request->has('end_date')
            ? \Carbon\Carbon::parse($request->end_date)
            : now();

        $totalDays = $startDate->diffInDays($endDate);
        
        if ($totalDays <= 0) {
            return 0;
        }

        $bookedNights = $room->bookings()
            ->where('status', 'confirmed')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->sum('nights');

        return round(($bookedNights / $totalDays) * 100, 2);
    }

    /**
     * Extract filters from request
     */
    private function getFilters(Request $request): array
    {
        $filters = [];

        if ($request->has('start_date') && $request->has('end_date')) {
            $filters['start_date'] = $request->start_date;
            $filters['end_date'] = $request->end_date;
        } else {
            $filters['start_date'] = now()->subDays(30)->toDateString();
            $filters['end_date'] = now()->toDateString();
        }

        return $filters;
    }

    /**
     * Export hotel report
     */
    private function exportReport(array $data, string $format, string $reportType, int $hotelId)
    {
        $hotel = Hotel::find($hotelId);
        $hotelName = $hotel ? str_replace(' ', '_', $hotel->name) : 'hotel';
        $filename = "hotel_analytics_{$hotelName}_{$reportType}_" . now()->format('Y-m-d_H-i-s');

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($data, $filename);
            case 'pdf':
            case 'excel':
            default:
                return response()->json([
                    'success' => true,
                    'message' => ucfirst($format) . ' export functionality ready for implementation',
                    'data' => $data
                ]);
        }
    }

    /**
     * Export data to CSV
     */
    private function exportToCsv(array $data, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Metric', 'Value']);
            $this->writeCsvData($file, $data, '');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Write array data to CSV recursively
     */
    private function writeCsvData($file, $data, $prefix = '')
    {
        foreach ($data as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            
            if (is_array($value)) {
                $this->writeCsvData($file, $value, $fullKey);
            } else {
                fputcsv($file, [$fullKey, $value]);
            }
        }
    }
}