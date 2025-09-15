@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="analytics-dashboard">
    <!-- Analytics Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Analytics Dashboard</h1>
            <p class="text-muted mb-0">Comprehensive business insights and reporting</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" id="exportReportBtn">
                <i class="bi bi-download"></i> Export Report
            </button>
            <button class="btn btn-primary" id="refreshDataBtn">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="analyticsFilters" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" id="dateRange">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 3 months</option>
                        <option value="365">Last 12 months</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3" id="customDateRange" style="display: none;">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="startDate" name="start_date" 
                           value="{{ $filters['start_date'] ?? '' }}">
                </div>
                <div class="col-md-3" id="customDateRangeEnd" style="display: none;">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDate" name="end_date" 
                           value="{{ $filters['end_date'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hotel</label>
                    <select class="form-select" id="hotelFilter" name="hotel_id">
                        <option value="">All Hotels</option>
                        <!-- Hotel options would be populated here -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-currency-dollar text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h4 class="mb-0" id="totalRevenue">
                                ${{ number_format($analytics['revenue']['total_revenue'] ?? 0, 2) }}
                            </h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> <span id="revenueChange">+12.3%</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar-check text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Bookings</h6>
                            <h4 class="mb-0" id="totalBookings">
                                {{ number_format($analytics['bookings']['total_bookings'] ?? 0) }}
                            </h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> <span id="bookingChange">+8.1%</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-house-fill text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Occupancy Rate</h6>
                            <h4 class="mb-0" id="occupancyRate">
                                {{ number_format($analytics['occupancy']['overall_occupancy_rate'] ?? 0, 1) }}%
                            </h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> <span id="occupancyChange">+2.4%</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-people-fill text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Customers</h6>
                            <h4 class="mb-0" id="totalCustomers">
                                {{ number_format($analytics['customers']['total_customers'] ?? 0) }}
                            </h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> <span id="customerChange">+15.2%</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Trends Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="card-title mb-0">Revenue Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueTrendsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Hotels by Revenue -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="card-title mb-0">Top Hotels by Revenue</h6>
                </div>
                <div class="card-body">
                    <canvas id="hotelRevenueChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics Row -->
    <div class="row mb-4">
        <!-- Booking Status Distribution -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="card-title mb-0">Booking Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="bookingStatusChart"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Confirmed</span>
                            <span class="fw-medium">{{ $analytics['bookings']['confirmed_bookings'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Pending</span>
                            <span class="fw-medium">{{ $analytics['bookings']['pending_bookings'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Cancelled</span>
                            <span class="fw-medium">{{ $analytics['bookings']['cancelled_bookings'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="card-title mb-0">Customer Segments</h6>
                </div>
                <div class="card-body">
                    <canvas id="customerSegmentsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Seasonal Patterns -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="card-title mb-0">Seasonal Booking Patterns</h6>
                </div>
                <div class="card-body">
                    <canvas id="seasonalPatternsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="card-title mb-0">Detailed Analytics</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="analyticsTable">
                            <thead>
                                <tr>
                                    <th>Hotel</th>
                                    <th>Revenue</th>
                                    <th>Bookings</th>
                                    <th>Occupancy</th>
                                    <th>Avg. Rate</th>
                                    <th>Customer Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Analytics Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select class="form-select" name="report_type" required>
                            <option value="comprehensive">Comprehensive Report</option>
                            <option value="revenue">Revenue Report</option>
                            <option value="bookings">Bookings Report</option>
                            <option value="occupancy">Occupancy Report</option>
                            <option value="customers">Customer Report</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Export Format</label>
                        <select class="form-select" name="format" required>
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="downloadReport">
                    <i class="bi bi-download"></i> Download Report
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Analytics data from backend
    const analyticsData = @json($analytics);
    
    // Initialize charts
    initializeRevenueTrendsChart();
    initializeHotelRevenueChart();
    initializeBookingStatusChart();
    initializeCustomerSegmentsChart();
    initializeSeasonalPatternsChart();
    
    // Initialize event listeners
    initializeEventListeners();
    
    function initializeRevenueTrendsChart() {
        const ctx = document.getElementById('revenueTrendsChart').getContext('2d');
        const monthlyRevenue = analyticsData.revenue.monthly_revenue || {};
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(monthlyRevenue),
                datasets: [{
                    label: 'Revenue',
                    data: Object.values(monthlyRevenue),
                    borderColor: 'rgb(13, 110, 253)',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    function initializeHotelRevenueChart() {
        const ctx = document.getElementById('hotelRevenueChart').getContext('2d');
        const revenueByHotel = analyticsData.revenue.revenue_by_hotel || {};
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(revenueByHotel),
                datasets: [{
                    data: Object.values(revenueByHotel),
                    backgroundColor: [
                        'rgb(13, 110, 253)',
                        'rgb(25, 135, 84)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)',
                        'rgb(111, 66, 193)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    function initializeBookingStatusChart() {
        const ctx = document.getElementById('bookingStatusChart').getContext('2d');
        const bookingData = analyticsData.bookings;
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Confirmed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [
                        bookingData.confirmed_bookings || 0,
                        bookingData.pending_bookings || 0,
                        bookingData.cancelled_bookings || 0
                    ],
                    backgroundColor: [
                        'rgb(25, 135, 84)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    function initializeCustomerSegmentsChart() {
        const ctx = document.getElementById('customerSegmentsChart').getContext('2d');
        const segments = analyticsData.customers.customer_segments || {};
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['High Value', 'Medium Value', 'Low Value'],
                datasets: [{
                    data: [
                        segments.high_value || 0,
                        segments.medium_value || 0,
                        segments.low_value || 0
                    ],
                    backgroundColor: [
                        'rgb(25, 135, 84)',
                        'rgb(255, 193, 7)',
                        'rgb(13, 110, 253)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function initializeSeasonalPatternsChart() {
        const ctx = document.getElementById('seasonalPatternsChart').getContext('2d');
        const patterns = analyticsData.bookings.seasonal_patterns || {};
        
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: Object.keys(patterns),
                datasets: [{
                    label: 'Bookings',
                    data: Object.values(patterns),
                    borderColor: 'rgb(13, 110, 253)',
                    backgroundColor: 'rgba(13, 110, 253, 0.2)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    function initializeEventListeners() {
        // Date range selector
        document.getElementById('dateRange').addEventListener('change', function() {
            const customRange = document.getElementById('customDateRange');
            const customRangeEnd = document.getElementById('customDateRangeEnd');
            
            if (this.value === 'custom') {
                customRange.style.display = 'block';
                customRangeEnd.style.display = 'block';
            } else {
                customRange.style.display = 'none';
                customRangeEnd.style.display = 'none';
                
                // Set dates based on selection
                const endDate = new Date();
                const startDate = new Date();
                startDate.setDate(startDate.getDate() - parseInt(this.value));
                
                document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
                document.getElementById('endDate').value = endDate.toISOString().split('T')[0];
            }
        });
        
        // Export report button
        document.getElementById('exportReportBtn').addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('exportModal')).show();
        });
        
        // Download report
        document.getElementById('downloadReport').addEventListener('click', function() {
            const form = document.getElementById('exportForm');
            const formData = new FormData(form);
            
            // Add current filters
            const filters = new FormData(document.getElementById('analyticsFilters'));
            for (let [key, value] of filters.entries()) {
                formData.append(key, value);
            }
            
            fetch('/admin/analytics/generate-report', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'analytics_report.csv';
                a.click();
                window.URL.revokeObjectURL(url);
            });
            
            bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
        });
        
        // Refresh data button
        document.getElementById('refreshDataBtn').addEventListener('click', function() {
            location.reload();
        });
        
        // Filter form submission
        document.getElementById('analyticsFilters').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const params = new URLSearchParams(formData);
            
            window.location.href = window.location.pathname + '?' + params.toString();
        });
    }
});
</script>
@endpush