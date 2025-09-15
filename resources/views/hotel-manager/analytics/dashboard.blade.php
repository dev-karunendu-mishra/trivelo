@extends('hotel-manager.layouts.app')

@section('title', 'Hotel Analytics')

@section('content')
<div class="analytics-dashboard">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Hotel Analytics</h1>
            <p class="text-muted mb-0">Performance insights for your properties</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" id="exportBtn">
                <i class="bi bi-download"></i> Export
            </button>
            <button class="btn btn-primary" id="refreshBtn">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Hotel Selector & Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filtersForm" class="row g-3">
                <div class="col-md-4">
                    <label for="hotelSelect" class="form-label">Select Hotel</label>
                    <select class="form-select" id="hotelSelect" name="hotel_id">
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ $hotel->id == $selectedHotel ? 'selected' : '' }}>
                                {{ $hotel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="dateRangeSelect" class="form-label">Period</label>
                    <select class="form-select" id="dateRangeSelect">
                        <option value="30">Last 30 days</option>
                        <option value="90">Last 3 months</option>
                        <option value="365">Last 12 months</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-md-2" id="startDateCol" style="display: none;">
                    <label for="startDate" class="form-label">Start</label>
                    <input type="date" class="form-control" id="startDate" name="start_date">
                </div>
                <div class="col-md-2" id="endDateCol" style="display: none;">
                    <label for="endDate" class="form-label">End</label>
                    <input type="date" class="form-control" id="endDate" name="end_date">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-currency-dollar fs-2"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 opacity-75">Revenue</h6>
                            <h3 class="mb-0" id="totalRevenue">
                                ${{ number_format($analytics['revenue']['total_revenue'] ?? 0, 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 opacity-75">Bookings</h6>
                            <h3 class="mb-0" id="totalBookings">
                                {{ number_format($analytics['bookings']['total_bookings'] ?? 0) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-house-fill fs-2"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 opacity-75">Occupancy</h6>
                            <h3 class="mb-0" id="occupancyRate">
                                {{ number_format($analytics['occupancy']['overall_occupancy_rate'] ?? 0, 1) }}%
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-star-fill fs-2"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 opacity-75">Avg Rating</h6>
                            <h3 class="mb-0" id="avgRating">
                                {{ number_format($analytics['customers']['customer_satisfaction']['average_rating'] ?? 0, 1) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Trends -->
        <div class="col-xl-8 col-lg-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">Revenue & Booking Trends</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="chartView" id="revenueView" value="revenue" checked>
                            <label class="btn btn-outline-primary btn-sm" for="revenueView">Revenue</label>
                            
                            <input type="radio" class="btn-check" name="chartView" id="bookingView" value="bookings">
                            <label class="btn btn-outline-primary btn-sm" for="bookingView">Bookings</label>
                        </div>
                    </div>
                    <canvas id="trendsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Room Performance -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">Room Performance</h6>
                </div>
                <div class="card-body">
                    <canvas id="roomPerformanceChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="row mb-4">
        <!-- Seasonal Patterns -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">Seasonal Booking Patterns</h6>
                </div>
                <div class="card-body">
                    <canvas id="seasonalChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">Key Performance Metrics</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-primary mb-1">
                                    ${{ number_format($analytics['revenue']['average_booking_value'] ?? 0, 0) }}
                                </h4>
                                <small class="text-muted">Avg Booking Value</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-success mb-1">
                                    {{ number_format($analytics['bookings']['average_stay_duration'] ?? 0, 1) }}
                                </h4>
                                <small class="text-muted">Avg Stay (nights)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-info mb-1">
                                    {{ number_format($analytics['bookings']['cancellation_rate'] ?? 0, 1) }}%
                                </h4>
                                <small class="text-muted">Cancellation Rate</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-warning mb-1">
                                    {{ $analytics['customers']['repeat_customers']['percentage'] ?? 0 }}%
                                </h4>
                                <small class="text-muted">Repeat Customers</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Room Analytics Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="card-title mb-0">Room Analytics</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="roomAnalyticsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Room</th>
                            <th>Type</th>
                            <th>Revenue</th>
                            <th>Bookings</th>
                            <th>Nights Booked</th>
                            <th>Avg Rate</th>
                            <th>Occupancy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data loaded via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Hotel Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <input type="hidden" name="hotel_id" id="exportHotelId">
                    <div class="mb-3">
                        <label for="reportType" class="form-label">Report Type</label>
                        <select class="form-select" id="reportType" name="report_type" required>
                            <option value="comprehensive">Comprehensive Report</option>
                            <option value="revenue">Revenue Analysis</option>
                            <option value="bookings">Booking Analysis</option>
                            <option value="occupancy">Occupancy Report</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exportFormat" class="form-label">Format</label>
                        <select class="form-select" id="exportFormat" name="format" required>
                            <option value="csv">CSV</option>
                            <option value="excel">Excel (Coming Soon)</option>
                            <option value="pdf">PDF (Coming Soon)</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exportStartDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="exportStartDate" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exportEndDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="exportEndDate" name="end_date" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="downloadBtn">
                    <i class="bi bi-download"></i> Generate Report
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
    // Data from backend
    const analyticsData = @json($analytics);
    const selectedHotelId = {{ $selectedHotel }};
    
    // Chart instances
    let trendsChart, roomChart, seasonalChart;
    
    // Initialize
    initializeCharts();
    loadRoomAnalytics();
    setupEventListeners();
    
    function initializeCharts() {
        initializeTrendsChart();
        initializeRoomPerformanceChart();
        initializeSeasonalChart();
    }
    
    function initializeTrendsChart() {
        const ctx = document.getElementById('trendsChart').getContext('2d');
        const monthlyRevenue = analyticsData.revenue.monthly_revenue || {};
        
        trendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(monthlyRevenue),
                datasets: [{
                    label: 'Revenue ($)',
                    data: Object.values(monthlyRevenue),
                    borderColor: 'rgb(102, 126, 234)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
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
    
    function initializeRoomPerformanceChart() {
        const ctx = document.getElementById('roomPerformanceChart').getContext('2d');
        const roomTypes = analyticsData.bookings.popular_room_types || {};
        
        roomChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(roomTypes),
                datasets: [{
                    data: Object.values(roomTypes),
                    backgroundColor: [
                        '#667eea', '#764ba2', '#f093fb', '#f5576c',
                        '#4facfe', '#00f2fe', '#43e97b', '#38f9d7'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
    
    function initializeSeasonalChart() {
        const ctx = document.getElementById('seasonalChart').getContext('2d');
        const patterns = analyticsData.bookings.seasonal_patterns || {};
        
        seasonalChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: Object.keys(patterns),
                datasets: [{
                    label: 'Bookings',
                    data: Object.values(patterns),
                    borderColor: 'rgb(245, 87, 108)',
                    backgroundColor: 'rgba(245, 87, 108, 0.2)',
                    pointBackgroundColor: 'rgb(245, 87, 108)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
    
    function loadRoomAnalytics() {
        fetch(`/hotel-manager/analytics/room-performance?hotel_id=${selectedHotelId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateRoomTable(data.data);
                }
            })
            .catch(error => console.error('Error loading room analytics:', error));
    }
    
    function populateRoomTable(roomData) {
        const tbody = document.querySelector('#roomAnalyticsTable tbody');
        tbody.innerHTML = '';
        
        roomData.forEach(room => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${room.room_number}</td>
                <td>${room.room_type}</td>
                <td>$${parseFloat(room.total_revenue).toLocaleString()}</td>
                <td>${room.total_bookings}</td>
                <td>${room.total_nights}</td>
                <td>$${parseFloat(room.average_rate).toFixed(2)}</td>
                <td>${room.occupancy_rate}%</td>
            `;
        });
    }
    
    function setupEventListeners() {
        // Date range selector
        document.getElementById('dateRangeSelect').addEventListener('change', function() {
            const startCol = document.getElementById('startDateCol');
            const endCol = document.getElementById('endDateCol');
            
            if (this.value === 'custom') {
                startCol.style.display = 'block';
                endCol.style.display = 'block';
            } else {
                startCol.style.display = 'none';
                endCol.style.display = 'none';
            }
        });
        
        // Chart view toggle
        document.querySelectorAll('input[name="chartView"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'revenue') {
                    updateTrendsChart(analyticsData.revenue.monthly_revenue, 'Revenue ($)', 'rgb(102, 126, 234)');
                } else {
                    updateTrendsChart(analyticsData.bookings.booking_trends, 'Bookings', 'rgb(245, 87, 108)');
                }
            });
        });
        
        // Export button
        document.getElementById('exportBtn').addEventListener('click', function() {
            document.getElementById('exportHotelId').value = selectedHotelId;
            new bootstrap.Modal(document.getElementById('exportModal')).show();
        });
        
        // Download report
        document.getElementById('downloadBtn').addEventListener('click', function() {
            const form = document.getElementById('exportForm');
            const formData = new FormData(form);
            
            fetch('/hotel-manager/analytics/generate-report', {
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
                a.download = 'hotel_analytics_report.csv';
                a.click();
                window.URL.revokeObjectURL(url);
            });
            
            bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
        });
        
        // Filter form
        document.getElementById('filtersForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData);
            window.location.href = window.location.pathname + '?' + params.toString();
        });
    }
    
    function updateTrendsChart(data, label, color) {
        trendsChart.data.datasets[0].data = Object.values(data);
        trendsChart.data.datasets[0].label = label;
        trendsChart.data.datasets[0].borderColor = color;
        trendsChart.data.datasets[0].backgroundColor = color.replace('rgb', 'rgba').replace(')', ', 0.1)');
        trendsChart.update();
    }
});
</script>
@endpush