@extends('hotel-manager.layouts.app')

@section('title', 'Guest Check-in')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Guest Check-in</h5>
                    <a href="{{ route('hotel-manager.guests') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Guests
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Process guest check-in for confirmed bookings.
                    </div>

                    <!-- Search for Booking -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-search"></i> Search Booking
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="booking_number" class="form-label">Booking Number</label>
                            <input type="text" class="form-control" id="booking_number" 
                                   placeholder="Enter booking number">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="guest_name" class="form-label">Guest Name</label>
                            <input type="text" class="form-control" id="guest_name" 
                                   placeholder="Enter guest name">
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="searchBooking()">
                                <i class="fas fa-search"></i> Search Booking
                            </button>
                        </div>
                    </div>

                    <!-- Booking Details (will be populated via AJAX) -->
                    <div id="booking-details" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-check-circle"></i> Booking Found
                                </h6>
                            </div>
                        </div>

                        <form action="{{ route('hotel-manager.guests.checkin.process') }}" method="POST">
                            @csrf
                            <input type="hidden" id="booking_id" name="booking_id">

                            <!-- Guest Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-user"></i> Guest Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>Name:</strong> <span id="guest-name-display"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Email:</strong> <span id="guest-email-display"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Phone:</strong> <span id="guest-phone-display"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Adults:</strong> <span id="guest-adults-display"></span>
                                            </div>
                                            <div class="mb-0">
                                                <strong>Children:</strong> <span id="guest-children-display"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-bed"></i> Booking Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>Booking Number:</strong> <span id="booking-number-display"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Room:</strong> <span id="room-display"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Check-in:</strong> <span id="checkin-date-display"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Check-out:</strong> <span id="checkout-date-display"></span>
                                            </div>
                                            <div class="mb-0">
                                                <strong>Nights:</strong> <span id="nights-display"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Check-in Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-key"></i> Check-in Details
                                    </h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="actual_checkin_time" class="form-label">Actual Check-in Time</label>
                                    <input type="datetime-local" class="form-control" 
                                           id="actual_checkin_time" name="actual_checkin_time" 
                                           value="{{ now()->format('Y-m-d\TH:i') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="room_key_issued" class="form-label">Room Key Issued</label>
                                    <select class="form-select" id="room_key_issued" name="room_key_issued">
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="checkin_notes" class="form-label">Check-in Notes</label>
                                    <textarea class="form-control" id="checkin_notes" name="checkin_notes" 
                                              rows="3" placeholder="Any special notes during check-in..."></textarea>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Complete Check-in
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- No booking found message -->
                    <div id="no-booking" class="alert alert-warning" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        No booking found with the provided details. Please check and try again.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchBooking() {
    const bookingNumber = document.getElementById('booking_number').value;
    const guestName = document.getElementById('guest_name').value;
    
    if (!bookingNumber && !guestName) {
        alert('Please enter booking number or guest name to search.');
        return;
    }
    
    // Here you would make an AJAX call to search for the booking
    // For now, we'll show a placeholder
    document.getElementById('booking-details').style.display = 'block';
    document.getElementById('no-booking').style.display = 'none';
    
    // Populate with placeholder data
    document.getElementById('booking-number-display').textContent = bookingNumber || 'TRV-2025-123456';
    document.getElementById('guest-name-display').textContent = guestName || 'John Doe';
    document.getElementById('guest-email-display').textContent = 'john.doe@example.com';
    document.getElementById('guest-phone-display').textContent = '+1 234 567 8900';
    document.getElementById('guest-adults-display').textContent = '2';
    document.getElementById('guest-children-display').textContent = '1';
    document.getElementById('room-display').textContent = 'Room 101 - Deluxe Room';
    document.getElementById('checkin-date-display').textContent = new Date().toDateString();
    document.getElementById('checkout-date-display').textContent = new Date(Date.now() + 86400000 * 3).toDateString();
    document.getElementById('nights-display').textContent = '3';
    
    // Set booking ID
    document.getElementById('booking_id').value = '1';
}

function resetForm() {
    document.getElementById('booking-details').style.display = 'none';
    document.getElementById('no-booking').style.display = 'none';
    document.getElementById('booking_number').value = '';
    document.getElementById('guest_name').value = '';
}
</script>
@endpush