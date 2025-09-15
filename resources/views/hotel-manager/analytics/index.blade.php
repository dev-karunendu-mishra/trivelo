@extends('hotel-manager.layouts.app')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Analytics')
@section('page-subtitle', 'Hotel performance metrics and insights')

@section('content')
    <!-- Key Metrics Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">${{ number_format($analytics['total_revenue'] ?? 0, 2) }}</h3>
                            <p class="card-text">Total Revenue</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +{{ number_format($analytics['revenue_growth'] ?? 0, 1) }}% from last month
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">{{ $analytics['total_bookings'] ?? 0 }}</h3>
                            <p class="card-text">Total Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +{{ $analytics['booking_growth'] ?? 0 }}% from last month
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">{{ number_format($analytics['occupancy_rate'] ?? 0, 1) }}%</h3>
                            <p class="card-text">Occupancy Rate</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bed fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-{{ ($analytics['occupancy_change'] ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i>
                            {{ ($analytics['occupancy_change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($analytics['occupancy_change'] ?? 0, 1) }}% from last month
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">${{ number_format($analytics['avg_daily_rate'] ?? 0, 2) }}</h3>
                            <p class="card-text">Avg Daily Rate</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-{{ ($analytics['adr_change'] ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i>
                            {{ ($analytics['adr_change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($analytics['adr_change'] ?? 0, 1) }}% from last month
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Revenue Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Booking Sources</h5>
                </div>
                <div class="card-body">
                    <canvas id="bookingSourceChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Room Type Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Room Type</th>
                                    <th>Bookings</th>
                                    <th>Revenue</th>
                                    <th>Occupancy</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($analytics['room_performance']) && is_array($analytics['room_performance']))
                                    @foreach($analytics['room_performance'] as $roomType => $performance)
                                        <tr>
                                            <td>{{ ucfirst($roomType) }}</td>
                                            <td>{{ $performance['bookings'] ?? 0 }}</td>
                                            <td>${{ number_format($performance['revenue'] ?? 0, 2) }}</td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ $performance['occupancy'] ?? 0 }}%" 
                                                         aria-valuenow="{{ $performance['occupancy'] ?? 0 }}" 
                                                         aria-valuemin="0" aria-valuemax="100">
                                                        {{ number_format($performance['occupancy'] ?? 0, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No room performance data available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Guest Satisfaction</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <h2 class="text-warning mb-0">{{ number_format($analytics['avg_rating'] ?? 0, 1) }}</h2>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= ($analytics['avg_rating'] ?? 0) ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Average Rating</p>
                            <p class="small text-muted mb-0">Based on {{ $analytics['total_reviews'] ?? 0 }} reviews</p>
                        </div>
                    </div>
                    
                    <div class="rating-breakdown">
                        @for($star = 5; $star >= 1; $star--)
                            <div class="d-flex align-items-center mb-1">
                                <small class="me-2">{{ $star }} star</small>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: {{ $analytics['rating_distribution'][$star] ?? 0 }}%"></div>
                                </div>
                                <small class="text-muted">{{ $analytics['rating_counts'][$star] ?? 0 }}</small>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Top Performing Metrics -->
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        @if(isset($analytics['recent_activities']) && is_array($analytics['recent_activities']))
                            @foreach($analytics['recent_activities'] as $activity)
                                <div class="activity-item d-flex align-items-start mb-3">
                                    <div class="activity-icon me-3">
                                        <i class="fas fa-{{ $activity['icon'] ?? 'circle' }} text-{{ $activity['color'] ?? 'primary' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1">{{ $activity['message'] ?? '' }}</p>
                                        <small class="text-muted">{{ $activity['time'] ?? '' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-chart-line fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No recent activity data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Insights</h5>
                </div>
                <div class="card-body">
                    <div class="insight-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Peak Booking Day</span>
                            <strong>{{ $analytics['peak_day'] ?? 'Saturday' }}</strong>
                        </div>
                    </div>
                    
                    <div class="insight-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Most Popular Room</span>
                            <strong>{{ $analytics['popular_room_type'] ?? 'Standard' }}</strong>
                        </div>
                    </div>
                    
                    <div class="insight-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Average Stay Duration</span>
                            <strong>{{ number_format($analytics['avg_stay_duration'] ?? 0, 1) }} nights</strong>
                        </div>
                    </div>
                    
                    <div class="insight-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Repeat Guest Rate</span>
                            <strong>{{ number_format($analytics['repeat_guest_rate'] ?? 0, 1) }}%</strong>
                        </div>
                    </div>
                    
                    <div class="insight-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Cancellation Rate</span>
                            <strong class="text-{{ ($analytics['cancellation_rate'] ?? 0) > 10 ? 'danger' : 'success' }}">
                                {{ number_format($analytics['cancellation_rate'] ?? 0, 1) }}%
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($analytics['revenue_labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']),
        datasets: [{
            label: 'Revenue',
            data: @json($analytics['revenue_data'] ?? [1200, 1900, 3000, 5000, 2000, 3000]),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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

// Booking Source Chart
const sourceCtx = document.getElementById('bookingSourceChart').getContext('2d');
const sourceChart = new Chart(sourceCtx, {
    type: 'doughnut',
    data: {
        labels: @json($analytics['source_labels'] ?? ['Direct', 'Online', 'Phone', 'Walk-in']),
        datasets: [{
            data: @json($analytics['source_data'] ?? [30, 45, 15, 10]),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0'
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
</script>
@endpush

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #20c997);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #6f42c1);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
}

.progress-sm {
    height: 6px;
}

.activity-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.insight-item {
    border-bottom: 1px solid #eee;
    padding-bottom: 0.75rem;
}

.insight-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.6) !important;
}
</style>
@endpush