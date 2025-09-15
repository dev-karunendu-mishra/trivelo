@extends('hotel-manager.layouts.app')

@section('title', 'Guest Check-out')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Guest Check-out</h5>
                    <a href="{{ route('hotel-manager.guests') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Guests
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Process guest check-out and generate final billing.
                    </div>

                    <!-- Search for Active Booking -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-search"></i> Search Active Booking
                            </h6>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="room_number" class="form-label">Room Number</label>
                            <input type="text" class="form-control" id="room_number" 
                                   placeholder="Enter room number">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="booking_number" class="form-label">Booking Number</label>
                            <input type="text" class="form-control" id="booking_number" 
                                   placeholder="Enter booking number">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="guest_name" class="form-label">Guest Name</label>
                            <input type="text" class="form-control" id="guest_name" 
                                   placeholder="Enter guest name">
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="searchActiveBooking()">
                                <i class="fas fa-search"></i> Search Active Booking
                            </button>
                        </div>
                    </div>

                    <!-- Active Bookings List -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-list"></i> Current Check-outs
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Room</th>
                                            <th>Guest Name</th>
                                            <th>Booking #</th>
                                            <th>Check-out Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Sample data - replace with dynamic content -->
                                        <tr>
                                            <td>101</td>
                                            <td>John Doe</td>
                                            <td>TRV-2025-123456</td>
                                            <td>{{ date('M d, Y') }}</td>
                                            <td><span class="badge bg-warning">Checked In</span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="processCheckout(1)">
                                                    <i class="fas fa-sign-out-alt"></i> Check Out
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>205</td>
                                            <td>Jane Smith</td>
                                            <td>TRV-2025-789012</td>
                                            <td>{{ date('M d, Y') }}</td>
                                            <td><span class="badge bg-warning">Checked In</span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="processCheckout(2)">
                                                    <i class="fas fa-sign-out-alt"></i> Check Out
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Check-out Processing Form (Hidden by default) -->
                    <div id="checkout-form" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-sign-out-alt"></i> Process Check-out
                                </h6>
                            </div>
                        </div>

                        <form action="{{ route('hotel-manager.guests.checkout.process') }}" method="POST">
                            @csrf
                            <input type="hidden" id="checkout_booking_id" name="booking_id">

                            <!-- Guest & Booking Summary -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-user"></i> Guest Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>Name:</strong> <span id="checkout-guest-name"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Room:</strong> <span id="checkout-room"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Booking:</strong> <span id="checkout-booking-number"></span>
                                            </div>
                                            <div class="mb-0">
                                                <strong>Nights Stayed:</strong> <span id="checkout-nights"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-dollar-sign"></i> Billing Summary</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>Room Charges:</strong> <span id="checkout-room-charges">$300.00</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Additional Charges:</strong> <span id="checkout-additional">$0.00</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Taxes:</strong> <span id="checkout-taxes">$30.00</span>
                                            </div>
                                            <hr>
                                            <div class="mb-0">
                                                <strong>Total:</strong> <span id="checkout-total" class="text-success">$330.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Check-out Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-clipboard-check"></i> Check-out Details
                                    </h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="actual_checkout_time" class="form-label">Actual Check-out Time</label>
                                    <input type="datetime-local" class="form-control" 
                                           id="actual_checkout_time" name="actual_checkout_time" 
                                           value="{{ now()->format('Y-m-d\TH:i') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="room_condition" class="form-label">Room Condition</label>
                                    <select class="form-select" id="room_condition" name="room_condition">
                                        <option value="clean" selected>Clean - Ready for next guest</option>
                                        <option value="maintenance">Needs Maintenance</option>
                                        <option value="deep_clean">Needs Deep Cleaning</option>
                                        <option value="damaged">Damaged - Report Required</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="key_returned" class="form-label">Room Key Returned</label>
                                    <select class="form-select" id="key_returned" name="key_returned">
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="payment_settled" class="form-label">Payment Settled</label>
                                    <select class="form-select" id="payment_settled" name="payment_settled">
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No - Outstanding Balance</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="checkout_notes" class="form-label">Check-out Notes</label>
                                    <textarea class="form-control" id="checkout_notes" name="checkout_notes" 
                                              rows="3" placeholder="Any issues, feedback, or special notes..."></textarea>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Complete Check-out
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="cancelCheckout()">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchActiveBooking() {
    const roomNumber = document.getElementById('room_number').value;
    const bookingNumber = document.getElementById('booking_number').value;
    const guestName = document.getElementById('guest_name').value;
    
    // Here you would make an AJAX call to filter the active bookings
    // For now, this is just a placeholder
    alert('Searching for bookings...');
}

function processCheckout(bookingId) {
    // Populate the checkout form with booking data
    document.getElementById('checkout_booking_id').value = bookingId;
    
    // Show the checkout form
    document.getElementById('checkout-form').style.display = 'block';
    
    // Populate with sample data (replace with actual data)
    document.getElementById('checkout-guest-name').textContent = 'John Doe';
    document.getElementById('checkout-room').textContent = 'Room 101 - Deluxe Room';
    document.getElementById('checkout-booking-number').textContent = 'TRV-2025-123456';
    document.getElementById('checkout-nights').textContent = '3';
    
    // Scroll to the form
    document.getElementById('checkout-form').scrollIntoView({ behavior: 'smooth' });
}

function cancelCheckout() {
    document.getElementById('checkout-form').style.display = 'none';
}
</script>
@endpush