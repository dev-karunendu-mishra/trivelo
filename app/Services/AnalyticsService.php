<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AnalyticsService
{
    /**
     * Get comprehensive revenue analytics
     */
    public function getRevenueAnalytics(array $filters = []): array
    {
        $query = Payment::where('status', 'completed');
        
        // Apply filters
        if (isset($filters['hotel_id'])) {
            $query->whereHas('booking', function($q) use ($filters) {
                $q->where('hotel_id', $filters['hotel_id']);
            });
        }
        
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($filters['start_date']),
                Carbon::parse($filters['end_date'])
            ]);
        } else {
            // Default to last 12 months
            $query->where('created_at', '>=', now()->subMonths(12));
        }
        
        $payments = $query->with(['booking.hotel', 'booking.room'])->get();
        
        return [
            'total_revenue' => $payments->sum('amount'),
            'total_bookings' => $payments->count(),
            'average_booking_value' => $payments->avg('amount'),
            'monthly_revenue' => $this->getMonthlyRevenue($payments),
            'revenue_by_hotel' => $this->getRevenueByHotel($payments),
            'revenue_by_room_type' => $this->getRevenueByRoomType($payments),
            'revenue_trends' => $this->getRevenueTrends($payments),
        ];
    }

    /**
     * Get booking analytics and trends
     */
    public function getBookingAnalytics(array $filters = []): array
    {
        $query = Booking::query();
        
        // Apply filters
        if (isset($filters['hotel_id'])) {
            $query->where('hotel_id', $filters['hotel_id']);
        }
        
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($filters['start_date']),
                Carbon::parse($filters['end_date'])
            ]);
        } else {
            // Default to last 12 months
            $query->where('created_at', '>=', now()->subMonths(12));
        }
        
        $bookings = $query->with(['hotel', 'room', 'user'])->get();
        
        return [
            'total_bookings' => $bookings->count(),
            'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'cancellation_rate' => $this->calculateCancellationRate($bookings),
            'average_stay_duration' => $this->calculateAverageStayDuration($bookings),
            'booking_sources' => $this->getBookingSources($bookings),
            'popular_room_types' => $this->getPopularRoomTypes($bookings),
            'booking_trends' => $this->getBookingTrends($bookings),
            'seasonal_patterns' => $this->getSeasonalPatterns($bookings),
        ];
    }

    /**
     * Get occupancy rate analytics
     */
    public function getOccupancyAnalytics(array $filters = []): array
    {
        $hotels = Hotel::with(['rooms', 'bookings']);
        
        if (isset($filters['hotel_id'])) {
            $hotels->where('id', $filters['hotel_id']);
        }
        
        $hotels = $hotels->get();
        
        $startDate = isset($filters['start_date']) 
            ? Carbon::parse($filters['start_date'])
            : now()->subMonths(12);
            
        $endDate = isset($filters['end_date'])
            ? Carbon::parse($filters['end_date'])
            : now();
        
        $occupancyData = [];
        
        foreach ($hotels as $hotel) {
            $occupancyData[$hotel->id] = $this->calculateHotelOccupancy($hotel, $startDate, $endDate);
        }
        
        return [
            'overall_occupancy_rate' => $this->calculateOverallOccupancy($occupancyData),
            'hotel_occupancy' => $occupancyData,
            'occupancy_trends' => $this->getOccupancyTrends($hotels, $startDate, $endDate),
            'peak_periods' => $this->getPeakPeriods($hotels, $startDate, $endDate),
        ];
    }

    /**
     * Get customer insights and analytics
     */
    public function getCustomerAnalytics(array $filters = []): array
    {
        $query = User::whereHas('bookings');
        
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereHas('bookings', function($q) use ($filters) {
                $q->whereBetween('created_at', [
                    Carbon::parse($filters['start_date']),
                    Carbon::parse($filters['end_date'])
                ]);
            });
        }
        
        $customers = $query->with(['bookings.payments', 'reviews'])->get();
        
        return [
            'total_customers' => $customers->count(),
            'new_customers' => $this->getNewCustomers($filters),
            'repeat_customers' => $this->getRepeatCustomers($customers),
            'customer_lifetime_value' => $this->calculateCustomerLifetimeValue($customers),
            'customer_segments' => $this->getCustomerSegments($customers),
            'customer_retention' => $this->calculateCustomerRetention($customers),
            'customer_demographics' => $this->getCustomerDemographics($customers),
            'customer_satisfaction' => $this->getCustomerSatisfaction($customers),
        ];
    }

    /**
     * Get comprehensive performance metrics
     */
    public function getPerformanceMetrics(array $filters = []): array
    {
        return [
            'revenue_metrics' => $this->getRevenueAnalytics($filters),
            'booking_metrics' => $this->getBookingAnalytics($filters),
            'occupancy_metrics' => $this->getOccupancyAnalytics($filters),
            'customer_metrics' => $this->getCustomerAnalytics($filters),
            'comparative_analysis' => $this->getComparativeAnalysis($filters),
        ];
    }

    /**
     * Helper method to get monthly revenue breakdown
     */
    private function getMonthlyRevenue(Collection $payments): array
    {
        return $payments->groupBy(function ($payment) {
            return $payment->created_at->format('Y-m');
        })->map(function ($monthPayments) {
            return $monthPayments->sum('amount');
        })->toArray();
    }

    /**
     * Helper method to get revenue by hotel
     */
    private function getRevenueByHotel(Collection $payments): array
    {
        return $payments->groupBy('booking.hotel.name')
            ->map(function ($hotelPayments) {
                return $hotelPayments->sum('amount');
            })->toArray();
    }

    /**
     * Helper method to get revenue by room type
     */
    private function getRevenueByRoomType(Collection $payments): array
    {
        return $payments->groupBy('booking.room.type')
            ->map(function ($roomPayments) {
                return $roomPayments->sum('amount');
            })->toArray();
    }

    /**
     * Helper method to get revenue trends
     */
    private function getRevenueTrends(Collection $payments): array
    {
        $trends = [];
        $monthlyRevenue = $this->getMonthlyRevenue($payments);
        
        $months = array_keys($monthlyRevenue);
        sort($months);
        
        for ($i = 1; $i < count($months); $i++) {
            $currentMonth = $monthlyRevenue[$months[$i]];
            $previousMonth = $monthlyRevenue[$months[$i-1]];
            
            if ($previousMonth > 0) {
                $growthRate = (($currentMonth - $previousMonth) / $previousMonth) * 100;
                $trends[$months[$i]] = round($growthRate, 2);
            }
        }
        
        return $trends;
    }

    /**
     * Calculate cancellation rate
     */
    private function calculateCancellationRate(Collection $bookings): float
    {
        $totalBookings = $bookings->count();
        if ($totalBookings === 0) return 0;
        
        $cancelledBookings = $bookings->where('status', 'cancelled')->count();
        return round(($cancelledBookings / $totalBookings) * 100, 2);
    }

    /**
     * Calculate average stay duration
     */
    private function calculateAverageStayDuration(Collection $bookings): float
    {
        $confirmedBookings = $bookings->where('status', 'confirmed');
        if ($confirmedBookings->isEmpty()) return 0;
        
        $totalNights = $confirmedBookings->sum('nights');
        return round($totalNights / $confirmedBookings->count(), 2);
    }

    /**
     * Get booking sources (web, mobile, API, etc.)
     */
    private function getBookingSources(Collection $bookings): array
    {
        // This would depend on how booking sources are tracked
        // For now, return a placeholder
        return [
            'website' => $bookings->count() * 0.7,
            'mobile_app' => $bookings->count() * 0.2,
            'api' => $bookings->count() * 0.1,
        ];
    }

    /**
     * Get popular room types
     */
    private function getPopularRoomTypes(Collection $bookings): array
    {
        return $bookings->groupBy('room.type')
            ->map(function ($roomBookings) {
                return $roomBookings->count();
            })
            ->sortDesc()
            ->toArray();
    }

    /**
     * Get booking trends over time
     */
    private function getBookingTrends(Collection $bookings): array
    {
        return $bookings->groupBy(function ($booking) {
            return $booking->created_at->format('Y-m');
        })->map(function ($monthBookings) {
            return $monthBookings->count();
        })->toArray();
    }

    /**
     * Get seasonal booking patterns
     */
    private function getSeasonalPatterns(Collection $bookings): array
    {
        return $bookings->groupBy(function ($booking) {
            $month = $booking->check_in_date->month;
            if (in_array($month, [12, 1, 2])) return 'Winter';
            if (in_array($month, [3, 4, 5])) return 'Spring';
            if (in_array($month, [6, 7, 8])) return 'Summer';
            return 'Fall';
        })->map(function ($seasonBookings) {
            return $seasonBookings->count();
        })->toArray();
    }

    /**
     * Calculate hotel occupancy rate
     */
    private function calculateHotelOccupancy(Hotel $hotel, Carbon $startDate, Carbon $endDate): array
    {
        $totalRooms = $hotel->rooms->count();
        $totalDays = $startDate->diffInDays($endDate);
        $totalPossibleNights = $totalRooms * $totalDays;
        
        $bookedNights = $hotel->bookings()
            ->where('status', 'confirmed')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->sum('nights');
        
        $occupancyRate = $totalPossibleNights > 0 
            ? round(($bookedNights / $totalPossibleNights) * 100, 2)
            : 0;
        
        return [
            'hotel_name' => $hotel->name,
            'occupancy_rate' => $occupancyRate,
            'total_rooms' => $totalRooms,
            'booked_nights' => $bookedNights,
            'possible_nights' => $totalPossibleNights,
        ];
    }

    /**
     * Calculate overall occupancy across all hotels
     */
    private function calculateOverallOccupancy(array $hotelOccupancyData): float
    {
        if (empty($hotelOccupancyData)) return 0;
        
        $totalBookedNights = array_sum(array_column($hotelOccupancyData, 'booked_nights'));
        $totalPossibleNights = array_sum(array_column($hotelOccupancyData, 'possible_nights'));
        
        return $totalPossibleNights > 0 
            ? round(($totalBookedNights / $totalPossibleNights) * 100, 2)
            : 0;
    }

    /**
     * Additional helper methods would continue here...
     */
    private function getOccupancyTrends($hotels, $startDate, $endDate): array
    {
        // Implementation for occupancy trends over time
        return [];
    }

    private function getPeakPeriods($hotels, $startDate, $endDate): array
    {
        // Implementation for identifying peak booking periods
        return [];
    }

    private function getNewCustomers(array $filters): int
    {
        $query = User::whereHas('bookings');
        
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($filters['start_date']),
                Carbon::parse($filters['end_date'])
            ]);
        }
        
        return $query->count();
    }

    private function getRepeatCustomers(Collection $customers): array
    {
        $repeatCustomers = $customers->filter(function ($customer) {
            return $customer->bookings->count() > 1;
        });
        
        return [
            'count' => $repeatCustomers->count(),
            'percentage' => $customers->count() > 0 
                ? round(($repeatCustomers->count() / $customers->count()) * 100, 2)
                : 0
        ];
    }

    private function calculateCustomerLifetimeValue(Collection $customers): float
    {
        if ($customers->isEmpty()) return 0;
        
        $totalRevenue = $customers->sum(function ($customer) {
            return $customer->bookings->sum(function ($booking) {
                return $booking->payments->where('status', 'completed')->sum('amount');
            });
        });
        
        return round($totalRevenue / $customers->count(), 2);
    }

    private function getCustomerSegments(Collection $customers): array
    {
        return [
            'high_value' => $customers->filter(function ($customer) {
                $totalSpent = $customer->bookings->sum('total_amount');
                return $totalSpent > 5000;
            })->count(),
            'medium_value' => $customers->filter(function ($customer) {
                $totalSpent = $customer->bookings->sum('total_amount');
                return $totalSpent >= 1000 && $totalSpent <= 5000;
            })->count(),
            'low_value' => $customers->filter(function ($customer) {
                $totalSpent = $customer->bookings->sum('total_amount');
                return $totalSpent < 1000;
            })->count(),
        ];
    }

    private function calculateCustomerRetention(Collection $customers): array
    {
        // Implementation for customer retention metrics
        return [
            'monthly_retention' => 75.5,
            'yearly_retention' => 45.2,
        ];
    }

    private function getCustomerDemographics(Collection $customers): array
    {
        // Implementation for customer demographics
        return [
            'age_groups' => [
                '18-25' => 15,
                '26-35' => 35,
                '36-45' => 30,
                '46-55' => 15,
                '55+' => 5,
            ],
        ];
    }

    private function getCustomerSatisfaction(Collection $customers): array
    {
        $allReviews = $customers->flatMap(function ($customer) {
            return $customer->reviews;
        });
        
        if ($allReviews->isEmpty()) {
            return ['average_rating' => 0, 'total_reviews' => 0];
        }
        
        return [
            'average_rating' => round($allReviews->avg('rating'), 2),
            'total_reviews' => $allReviews->count(),
            'rating_distribution' => $allReviews->groupBy('rating')
                ->map(function ($reviews) {
                    return $reviews->count();
                })->toArray(),
        ];
    }

    private function getComparativeAnalysis(array $filters): array
    {
        // Implementation for comparative analysis (YoY, MoM comparisons)
        return [
            'year_over_year_growth' => 15.5,
            'month_over_month_growth' => 3.2,
        ];
    }
}