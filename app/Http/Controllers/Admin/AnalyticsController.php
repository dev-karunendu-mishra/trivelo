<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->middleware('auth');
        $this->middleware('role:super-admin|admin');
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display analytics dashboard
     */
    public function dashboard(Request $request)
    {
        $filters = $this->getFilters($request);
        
        $analytics = [
            'revenue' => $this->analyticsService->getRevenueAnalytics($filters),
            'bookings' => $this->analyticsService->getBookingAnalytics($filters),
            'occupancy' => $this->analyticsService->getOccupancyAnalytics($filters),
            'customers' => $this->analyticsService->getCustomerAnalytics($filters),
        ];

        return view('admin.analytics.dashboard', compact('analytics', 'filters'));
    }

    /**
     * Get revenue analytics API endpoint
     */
    public function revenueAnalytics(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request);
        $analytics = $this->analyticsService->getRevenueAnalytics($filters);

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get booking analytics API endpoint
     */
    public function bookingAnalytics(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request);
        $analytics = $this->analyticsService->getBookingAnalytics($filters);

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get occupancy analytics API endpoint
     */
    public function occupancyAnalytics(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request);
        $analytics = $this->analyticsService->getOccupancyAnalytics($filters);

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get customer analytics API endpoint
     */
    public function customerAnalytics(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request);
        $analytics = $this->analyticsService->getCustomerAnalytics($filters);

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Generate comprehensive analytics report
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:revenue,bookings,occupancy,customers,comprehensive',
            'format' => 'required|in:pdf,excel,csv',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'hotel_id' => 'nullable|exists:hotels,id',
        ]);

        $filters = $this->getFilters($request);
        
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
            case 'customers':
                $data = $this->analyticsService->getCustomerAnalytics($filters);
                break;
            case 'comprehensive':
            default:
                $data = $this->analyticsService->getPerformanceMetrics($filters);
                break;
        }

        return $this->exportReport($data, $request->format, $request->report_type);
    }

    /**
     * Get comparative analytics (month-over-month, year-over-year)
     */
    public function comparativeAnalytics(Request $request): JsonResponse
    {
        $currentPeriod = $this->getFilters($request);
        
        // Calculate previous period dates
        $startDate = Carbon::parse($currentPeriod['start_date']);
        $endDate = Carbon::parse($currentPeriod['end_date']);
        $periodLength = $startDate->diffInDays($endDate);
        
        $previousPeriod = [
            'start_date' => $startDate->copy()->subDays($periodLength + 1)->toDateString(),
            'end_date' => $startDate->copy()->subDay()->toDateString(),
        ];
        
        if (isset($currentPeriod['hotel_id'])) {
            $previousPeriod['hotel_id'] = $currentPeriod['hotel_id'];
        }

        $currentData = $this->analyticsService->getPerformanceMetrics($currentPeriod);
        $previousData = $this->analyticsService->getPerformanceMetrics($previousPeriod);

        $comparison = $this->calculateComparison($currentData, $previousData);

        return response()->json([
            'success' => true,
            'data' => [
                'current_period' => $currentData,
                'previous_period' => $previousData,
                'comparison' => $comparison,
            ]
        ]);
    }

    /**
     * Get real-time analytics dashboard data
     */
    public function realTimeAnalytics(): JsonResponse
    {
        $today = [
            'start_date' => now()->startOfDay()->toDateString(),
            'end_date' => now()->endOfDay()->toDateString(),
        ];

        $todayData = $this->analyticsService->getPerformanceMetrics($today);

        // Add real-time specific metrics
        $realTimeMetrics = [
            'active_bookings_today' => $todayData['booking_metrics']['total_bookings'],
            'revenue_today' => $todayData['revenue_metrics']['total_revenue'],
            'new_customers_today' => $todayData['customer_metrics']['new_customers'],
            'current_occupancy' => $todayData['occupancy_metrics']['overall_occupancy_rate'],
        ];

        return response()->json([
            'success' => true,
            'data' => $realTimeMetrics
        ]);
    }

    /**
     * Extract and validate filters from request
     */
    private function getFilters(Request $request): array
    {
        $filters = [];

        if ($request->has('start_date') && $request->has('end_date')) {
            $filters['start_date'] = $request->start_date;
            $filters['end_date'] = $request->end_date;
        } else {
            // Default to last 30 days
            $filters['start_date'] = now()->subDays(30)->toDateString();
            $filters['end_date'] = now()->toDateString();
        }

        if ($request->filled('hotel_id')) {
            $filters['hotel_id'] = $request->hotel_id;
        }

        return $filters;
    }

    /**
     * Export report in specified format
     */
    private function exportReport(array $data, string $format, string $reportType)
    {
        $filename = "analytics_report_{$reportType}_" . now()->format('Y-m-d_H-i-s');

        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($data, $filename);
            case 'excel':
                return $this->exportToExcel($data, $filename);
            case 'csv':
                return $this->exportToCsv($data, $filename);
            default:
                return response()->json(['error' => 'Invalid format'], 400);
        }
    }

    /**
     * Export data to PDF
     */
    private function exportToPdf(array $data, string $filename)
    {
        // Implementation would use a PDF library like DomPDF or wkhtmltopdf
        // For now, return JSON response indicating feature availability
        return response()->json([
            'success' => true,
            'message' => 'PDF export functionality ready for implementation',
            'data' => $data
        ]);
    }

    /**
     * Export data to Excel
     */
    private function exportToExcel(array $data, string $filename)
    {
        // Implementation would use Laravel Excel package
        // For now, return JSON response indicating feature availability
        return response()->json([
            'success' => true,
            'message' => 'Excel export functionality ready for implementation',
            'data' => $data
        ]);
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
            
            // Write headers
            fputcsv($file, ['Metric', 'Value']);
            
            // Write data (simplified version)
            $this->writeCsvData($file, $data, '');
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Recursively write array data to CSV
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

    /**
     * Calculate comparison metrics between current and previous periods
     */
    private function calculateComparison(array $current, array $previous): array
    {
        $comparison = [];
        
        // Revenue comparison
        if (isset($current['revenue_metrics']['total_revenue']) && isset($previous['revenue_metrics']['total_revenue'])) {
            $currentRevenue = $current['revenue_metrics']['total_revenue'];
            $previousRevenue = $previous['revenue_metrics']['total_revenue'];
            
            $comparison['revenue_change'] = $previousRevenue > 0 
                ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 2)
                : 0;
        }

        // Booking comparison
        if (isset($current['booking_metrics']['total_bookings']) && isset($previous['booking_metrics']['total_bookings'])) {
            $currentBookings = $current['booking_metrics']['total_bookings'];
            $previousBookings = $previous['booking_metrics']['total_bookings'];
            
            $comparison['booking_change'] = $previousBookings > 0 
                ? round((($currentBookings - $previousBookings) / $previousBookings) * 100, 2)
                : 0;
        }

        // Occupancy comparison
        if (isset($current['occupancy_metrics']['overall_occupancy_rate']) && isset($previous['occupancy_metrics']['overall_occupancy_rate'])) {
            $currentOccupancy = $current['occupancy_metrics']['overall_occupancy_rate'];
            $previousOccupancy = $previous['occupancy_metrics']['overall_occupancy_rate'];
            
            $comparison['occupancy_change'] = $currentOccupancy - $previousOccupancy;
        }

        return $comparison;
    }
}