@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Dashboard Overview</h1>
            <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name ?? 'Admin' }}! Here's what's happening today.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="refreshDashboard()">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2"></i>PDF Report</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i>Excel Report</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i>CSV Export</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Key Metrics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Bookings -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-gradient rounded-3 p-3">
                                <i class="bi bi-calendar-check text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Bookings</h6>
                                    <h3 class="mb-0">{{ $metrics['total_bookings'] ?? '1,247' }}</h3>
                                </div>
                                <div class="text-end">
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> +12.5%
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted">From last month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-gradient rounded-3 p-3">
                                <i class="bi bi-currency-dollar text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Revenue</h6>
                                    <h3 class="mb-0">${{ number_format($metrics['total_revenue'] ?? 289500, 0) }}</h3>
                                </div>
                                <div class="text-end">
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> +8.2%
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted">From last month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Occupancy Rate -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-gradient rounded-3 p-3">
                                <i class="bi bi-pie-chart text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Occupancy Rate</h6>
                                    <h3 class="mb-0">{{ $metrics['occupancy_rate'] ?? '78.5' }}%</h3>
                                </div>
                                <div class="text-end">
                                    <small class="text-danger">
                                        <i class="bi bi-arrow-down"></i> -2.1%
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted">From last month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Users -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-gradient rounded-3 p-3">
                                <i class="bi bi-people text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Active Users</h6>
                                    <h3 class="mb-0">{{ $metrics['active_users'] ?? '8,392' }}</h3>
                                </div>
                                <div class="text-end">
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> +15.3%
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted">From last month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Booking Trends Chart -->
        <div class="col-xl-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Booking Trends</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="booking-period" id="booking-7d" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="booking-7d">7D</label>
                            
                            <input type="radio" class="btn-check" name="booking-period" id="booking-30d" autocomplete="off">
                            <label class="btn btn-outline-primary" for="booking-30d">30D</label>
                            
                            <input type="radio" class="btn-check" name="booking-period" id="booking-90d" autocomplete="off">
                            <label class="btn btn-outline-primary" for="booking-90d">90D</label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="bookingTrendsChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Revenue Distribution -->
        <div class="col-xl-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Revenue by Hotel Type</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <canvas id="revenueDistributionChart" class="flex-grow-1"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <small>Luxury</small>
                            </div>
                            <small class="fw-semibold">45%</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <small>Business</small>
                            </div>
                            <small class="fw-semibold">30%</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <small>Budget</small>
                            </div>
                            <small class="fw-semibold">25%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities and Quick Actions -->
    <div class="row g-4">
        <!-- Recent Activities -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Activities</h5>
                        <a href="#" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Activity</th>
                                    <th>User</th>
                                    <th>Hotel</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="bi bi-calendar-plus text-success me-2"></i>
                                        New Booking
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32x32/6c757d/ffffff?text=JD" 
                                                 class="rounded-circle me-2" width="32" height="32" alt="User">
                                            <div>
                                                <div class="fw-semibold">John Doe</div>
                                                <small class="text-muted">john@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Grand Plaza Hotel</td>
                                    <td>$450.00</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                    <td><small class="text-muted">2 min ago</small></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="bi bi-person-plus text-info me-2"></i>
                                        User Registration
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32x32/6c757d/ffffff?text=SM" 
                                                 class="rounded-circle me-2" width="32" height="32" alt="User">
                                            <div>
                                                <div class="fw-semibold">Sarah Miller</div>
                                                <small class="text-muted">sarah@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><span class="badge bg-info">Active</span></td>
                                    <td><small class="text-muted">5 min ago</small></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="bi bi-credit-card text-primary me-2"></i>
                                        Payment Received
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32x32/6c757d/ffffff?text=MB" 
                                                 class="rounded-circle me-2" width="32" height="32" alt="User">
                                            <div>
                                                <div class="fw-semibold">Mike Brown</div>
                                                <small class="text-muted">mike@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Ocean View Resort</td>
                                    <td>$890.00</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                    <td><small class="text-muted">8 min ago</small></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="bi bi-star text-warning me-2"></i>
                                        New Review
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32x32/6c757d/ffffff?text=LW" 
                                                 class="rounded-circle me-2" width="32" height="32" alt="User">
                                            <div>
                                                <div class="fw-semibold">Lisa Wilson</div>
                                                <small class="text-muted">lisa@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>City Center Hotel</td>
                                    <td>-</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="bi bi-star-fill text-warning small"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td><small class="text-muted">12 min ago</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" onclick="addNewHotel()">
                            <i class="bi bi-building me-2"></i>
                            Add New Hotel
                        </button>
                        <button class="btn btn-success btn-lg" onclick="processPendingBookings()">
                            <i class="bi bi-calendar-check me-2"></i>
                            Process Pending Bookings
                            <span class="badge bg-white text-success ms-2">12</span>
                        </button>
                        <button class="btn btn-info btn-lg" onclick="manageUsers()">
                            <i class="bi bi-people me-2"></i>
                            Manage Users
                        </button>
                        <button class="btn btn-warning btn-lg" onclick="generateReports()">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i>
                            Generate Reports
                        </button>
                    </div>
                    
                    <!-- System Status -->
                    <div class="mt-4">
                        <h6 class="mb-3">System Status</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small>Server Health</small>
                            <span class="badge bg-success">Excellent</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small>Database</small>
                            <span class="badge bg-success">Online</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small>Payment Gateway</small>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small>Email Service</small>
                            <span class="badge bg-warning">Maintenance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Booking Trends Chart
    const bookingTrendsCtx = document.getElementById('bookingTrendsChart').getContext('2d');
    const bookingTrendsChart = new Chart(bookingTrendsCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Bookings',
                data: [12, 19, 15, 25, 22, 30, 28],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Revenue ($)',
                data: [1200, 1900, 1500, 2500, 2200, 3000, 2800],
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
    
    // Initialize Revenue Distribution Chart
    const revenueDistributionCtx = document.getElementById('revenueDistributionChart').getContext('2d');
    const revenueDistributionChart = new Chart(revenueDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Luxury', 'Business', 'Budget'],
            datasets: [{
                data: [45, 30, 25],
                backgroundColor: ['#0d6efd', '#198754', '#17a2b8'],
                borderWidth: 0
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
});

// Quick Action Functions
function refreshDashboard() {
    location.reload();
}

function addNewHotel() {
    // Redirect to add hotel page
    window.location.href = '#'; // Replace with actual route
}

function processPendingBookings() {
    // Redirect to pending bookings page
    window.location.href = '#'; // Replace with actual route
}

function manageUsers() {
    window.location.href = '{{ route('admin.users') }}';
}

function generateReports() {
    // Redirect to reports page
    window.location.href = '#'; // Replace with actual route
}
</script>
@endpush