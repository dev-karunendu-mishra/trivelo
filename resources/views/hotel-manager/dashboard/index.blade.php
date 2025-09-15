@extends('hotel-manager.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Hotel Management Overview')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-6 fw-bold mb-2">Welcome back, {{ $user->name }}!</h1>
                    <p class="mb-0 opacity-75">Here's what's happening at {{ $hotel->name ?? 'your hotel' }} today.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button class="btn btn-light btn-lg me-2" onclick="refreshDashboard()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-success btn-lg dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-plus-lg me-2"></i>Quick Add
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-calendar-plus me-2"></i>New Booking</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-door-open me-2"></i>Add Room</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person-plus me-2"></i>Walk-in Guest</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!$hotel)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Setup Required:</strong> Please set up your hotel profile to start managing bookings and rooms.
            <a href="#" class="btn btn-sm btn-warning ms-3">Set Up Hotel</a>
        </div>
    @else
        <!-- Key Metrics -->
        <div class="row g-4 mb-4">
            <!-- Occupancy Rate -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-pie-chart fs-1 opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-semibold opacity-75">Occupancy Rate</div>
                                <div class="display-6 fw-bold">{{ $stats['occupancy_rate'] }}%</div>
                                <small class="opacity-75">{{ $stats['occupied_rooms'] }}/{{ $stats['total_rooms'] }} rooms</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Rooms -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-door-open fs-1 opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-semibold opacity-75">Available Rooms</div>
                                <div class="display-6 fw-bold">{{ $stats['available_rooms'] }}</div>
                                <small class="opacity-75">Ready for booking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Bookings -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-check fs-1 opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-semibold opacity-75">Today's Bookings</div>
                                <div class="display-6 fw-bold">{{ $stats['bookings_today'] }}</div>
                                <small class="opacity-75">New reservations</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-currency-dollar fs-1 opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-semibold opacity-75">Monthly Revenue</div>
                                <div class="display-6 fw-bold">${{ number_format($stats['monthly_revenue']) }}</div>
                                <small class="opacity-75">
                                    @if($stats['revenue_growth'] >= 0)
                                        <i class="bi bi-arrow-up"></i> +{{ $stats['revenue_growth'] }}%
                                    @else
                                        <i class="bi bi-arrow-down"></i> {{ $stats['revenue_growth'] }}%
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Row -->
        <div class="row g-4 mb-4">
            <!-- Revenue Chart -->
            <div class="col-xl-8">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">7-Day Revenue Trend</h5>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary active">7D</button>
                                <button class="btn btn-outline-primary">30D</button>
                                <button class="btn btn-outline-primary">90D</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Room Status -->
            <div class="col-xl-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Room Status</h5>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="text-center mb-4">
                            <canvas id="roomStatusChart" width="200" height="200"></canvas>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                    <div class="fw-bold text-success fs-4">{{ $roomStatus['available'] }}</div>
                                    <small class="text-muted">Available</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                    <div class="fw-bold text-primary fs-4">{{ $roomStatus['occupied'] }}</div>
                                    <small class="text-muted">Occupied</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                    <div class="fw-bold text-warning fs-4">{{ $roomStatus['maintenance'] }}</div>
                                    <small class="text-muted">Maintenance</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                                    <div class="fw-bold text-danger fs-4">{{ $roomStatus['out_of_order'] }}</div>
                                    <small class="text-muted">Out of Order</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Row -->
        <div class="row g-4">
            <!-- Upcoming Check-ins -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Today's Check-ins</h5>
                            <span class="badge bg-warning">{{ $upcomingCheckins->count() }} pending</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($upcomingCheckins->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Guest</th>
                                            <th>Room</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingCheckins as $checkin)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/32x32/6c757d/ffffff?text={{ substr($checkin->user->name, 0, 1) }}" 
                                                         class="rounded-circle me-2" width="32" height="32">
                                                    <div>
                                                        <div class="fw-semibold">{{ $checkin->user->name }}</div>
                                                        <small class="text-muted">{{ $checkin->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $checkin->room->room_number }}</span><br>
                                                <small class="text-muted">{{ $checkin->room->type }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $checkin->check_in->format('H:i') }}</div>
                                                <small class="text-muted">Check-in time</small>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-gradient">Check In</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x text-muted fs-1"></i>
                                <div class="mt-3 text-muted">No check-ins scheduled for today</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Bookings</h5>
                            <a href="{{ route('hotel-manager.bookings') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($recentBookings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Guest</th>
                                            <th>Room</th>
                                            <th>Dates</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $booking->user->name }}</div>
                                                <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $booking->room->room_number }}</span><br>
                                                <small class="text-muted">${{ number_format($booking->total_amount) }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $booking->check_in->format('M j') }} - {{ $booking->check_out->format('M j') }}</div>
                                                <small class="text-muted">{{ $booking->nights }} {{ Str::plural('night', $booking->nights) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-bookmark text-muted fs-1"></i>
                                <div class="mt-3 text-muted">No recent bookings</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($hotel)
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($revenueData)->pluck('date')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode(collect($revenueData)->pluck('revenue')) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
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

    // Room Status Chart
    const roomStatusCtx = document.getElementById('roomStatusChart').getContext('2d');
    const roomStatusChart = new Chart(roomStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Occupied', 'Maintenance', 'Out of Order'],
            datasets: [{
                data: [
                    {{ $roomStatus['available'] }},
                    {{ $roomStatus['occupied'] }},
                    {{ $roomStatus['maintenance'] }},
                    {{ $roomStatus['out_of_order'] }}
                ],
                backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#dc3545'],
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
    @endif
});

function refreshDashboard() {
    window.location.reload();
}
</script>
@endpush