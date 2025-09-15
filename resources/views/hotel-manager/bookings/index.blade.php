@extends('hotel-manager.layouts.app')

@section('title', 'Bookings Management')
@section('page-title', 'Bookings')
@section('page-subtitle', 'Manage all hotel bookings and reservations')

@section('content')
    <!-- Booking Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">{{ $bookingStats['total'] ?? 0 }}</h3>
                            <p class="card-text">Total Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">{{ $bookingStats['confirmed'] ?? 0 }}</h3>
                            <p class="card-text">Confirmed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">{{ $bookingStats['pending'] ?? 0 }}</h3>
                            <p class="card-text">Pending</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">{{ $bookingStats['cancelled'] ?? 0 }}</h3>
                            <p class="card-text">Cancelled</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Booking Management</h5>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBookingModal">
                        <i class="fas fa-plus"></i> New Booking
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="checked_in">Checked In</option>
                        <option value="checked_out">Checked Out</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="dateFilter" class="form-label">Check-in Date</label>
                    <input type="date" class="form-control" id="dateFilter">
                </div>
                <div class="col-md-3">
                    <label for="searchFilter" class="form-label">Search Guest</label>
                    <input type="text" class="form-control" id="searchFilter" placeholder="Guest name or email">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Recent Bookings</h5>
        </div>
        <div class="card-body">
            @if($bookings && $bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Booking ID</th>
                                <th>Guest Name</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Nights</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>#{{ $booking->id }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $booking->guest_name ?? $booking->user->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $booking->guest_email ?? $booking->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $booking->room->room_number ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking->room->room_type ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if($booking->check_in_date && $booking->check_out_date)
                                            {{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <strong>${{ number_format($booking->total_amount ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'checked_in' => 'info',
                                                'checked_out' => 'secondary',
                                                'cancelled' => 'danger'
                                            ][$booking->status ?? 'pending'] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($booking->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewBookingModal"
                                                    data-booking-id="{{ $booking->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($booking->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="confirmBooking({{ $booking->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            @if($booking->status === 'confirmed')
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        onclick="checkInGuest({{ $booking->id }})">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </button>
                                            @endif
                                            @if($booking->status === 'checked_in')
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="checkOutGuest({{ $booking->id }})">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="cancelBooking({{ $booking->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($bookings, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No bookings found</h4>
                    <p class="text-muted">Start accepting bookings for your hotel rooms.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBookingModal">
                        <i class="fas fa-plus"></i> Create First Booking
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Booking Modal -->
    <div class="modal fade" id="createBookingModal" tabindex="-1" aria-labelledby="createBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBookingModalLabel">Create New Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createBookingForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="guest_name" class="form-label">Guest Name *</label>
                                    <input type="text" class="form-control" id="guest_name" name="guest_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="guest_email" class="form-label">Guest Email *</label>
                                    <input type="email" class="form-control" id="guest_email" name="guest_email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="guest_phone" class="form-label">Guest Phone</label>
                                    <input type="tel" class="form-control" id="guest_phone" name="guest_phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_id" class="form-label">Room *</label>
                                    <select class="form-select" id="room_id" name="room_id" required>
                                        <option value="">Select a room</option>
                                        @if($hotel && $hotel->rooms)
                                            @foreach($hotel->rooms->where('is_available', true) as $room)
                                                <option value="{{ $room->id }}">
                                                    {{ $room->room_number }} - {{ $room->room_type }} ({{ $room->status }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_in_date" class="form-label">Check-in Date *</label>
                                    <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_out_date" class="form-label">Check-out Date *</label>
                                    <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adults" class="form-label">Adults</label>
                                    <select class="form-select" id="adults" name="adults">
                                        <option value="1">1 Adult</option>
                                        <option value="2">2 Adults</option>
                                        <option value="3">3 Adults</option>
                                        <option value="4">4 Adults</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="children" class="form-label">Children</label>
                                    <select class="form-select" id="children" name="children">
                                        <option value="0">0 Children</option>
                                        <option value="1">1 Child</option>
                                        <option value="2">2 Children</option>
                                        <option value="3">3 Children</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="special_requests" class="form-label">Special Requests</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createBooking()">Create Booking</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function createBooking() {
    // Implementation for creating booking
    alert('Booking creation functionality to be implemented');
}

function confirmBooking(bookingId) {
    if (confirm('Are you sure you want to confirm this booking?')) {
        // Implementation for confirming booking
        alert('Booking confirmation functionality to be implemented');
    }
}

function checkInGuest(bookingId) {
    if (confirm('Are you sure you want to check in this guest?')) {
        // Implementation for guest check-in
        alert('Check-in functionality to be implemented');
    }
}

function checkOutGuest(bookingId) {
    if (confirm('Are you sure you want to check out this guest?')) {
        // Implementation for guest check-out
        alert('Check-out functionality to be implemented');
    }
}

function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
        // Implementation for cancelling booking
        alert('Booking cancellation functionality to be implemented');
    }
}

// Set minimum date for check-in to today
document.getElementById('check_in_date').min = new Date().toISOString().split('T')[0];

// Update check-out minimum date when check-in changes
document.getElementById('check_in_date').addEventListener('change', function() {
    var checkInDate = new Date(this.value);
    checkInDate.setDate(checkInDate.getDate() + 1);
    document.getElementById('check_out_date').min = checkInDate.toISOString().split('T')[0];
});
</script>
@endpush