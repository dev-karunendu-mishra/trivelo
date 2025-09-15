@extends('hotel-manager.layouts.app')

@section('title', 'Manual Booking')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create Manual Booking</h5>
                    <a href="{{ route('hotel-manager.bookings') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Bookings
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Create a booking manually for walk-in guests or phone reservations.
                    </div>

                    <form action="{{ route('hotel-manager.bookings.store') }}" method="POST">
                        @csrf

                        <!-- Guest Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user"></i> Guest Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="guest_first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('guest_first_name') is-invalid @enderror" 
                                       id="guest_first_name" name="guest_first_name" value="{{ old('guest_first_name') }}" required>
                                @error('guest_first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="guest_last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('guest_last_name') is-invalid @enderror" 
                                       id="guest_last_name" name="guest_last_name" value="{{ old('guest_last_name') }}" required>
                                @error('guest_last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="guest_email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('guest_email') is-invalid @enderror" 
                                       id="guest_email" name="guest_email" value="{{ old('guest_email') }}" required>
                                @error('guest_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="guest_phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('guest_phone') is-invalid @enderror" 
                                       id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}">
                                @error('guest_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-calendar"></i> Booking Details
                                </h6>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="room_id" class="form-label">Room *</label>
                                <select class="form-select @error('room_id') is-invalid @enderror" 
                                        id="room_id" name="room_id" required>
                                    <option value="">Select Room</option>
                                    <!-- Room options will be populated -->
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="check_in_date" class="form-label">Check-in Date *</label>
                                <input type="date" class="form-control @error('check_in_date') is-invalid @enderror" 
                                       id="check_in_date" name="check_in_date" value="{{ old('check_in_date') }}" required>
                                @error('check_in_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="check_out_date" class="form-label">Check-out Date *</label>
                                <input type="date" class="form-control @error('check_out_date') is-invalid @enderror" 
                                       id="check_out_date" name="check_out_date" value="{{ old('check_out_date') }}" required>
                                @error('check_out_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="adults" class="form-label">Adults *</label>
                                <select class="form-select @error('adults') is-invalid @enderror" 
                                        id="adults" name="adults" required>
                                    <option value="">Select Adults</option>
                                    @for($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ old('adults') == $i ? 'selected' : '' }}>
                                            {{ $i }} Adult{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('adults')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="children" class="form-label">Children</label>
                                <select class="form-select @error('children') is-invalid @enderror" 
                                        id="children" name="children">
                                    @for($i = 0; $i <= 3; $i++)
                                        <option value="{{ $i }}" {{ old('children') == $i ? 'selected' : '' }}>
                                            {{ $i }} Child{{ $i != 1 ? 'ren' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('children')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="special_requests" class="form-label">Special Requests</label>
                                <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                          id="special_requests" name="special_requests" rows="3">{{ old('special_requests') }}</textarea>
                                @error('special_requests')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-dollar-sign"></i> Pricing
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="room_rate" class="form-label">Room Rate (per night) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('room_rate') is-invalid @enderror" 
                                           id="room_rate" name="room_rate" value="{{ old('room_rate') }}" required>
                                </div>
                                @error('room_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="payment_status" class="form-label">Payment Status *</label>
                                <select class="form-select @error('payment_status') is-invalid @enderror" 
                                        id="payment_status" name="payment_status" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partially Paid</option>
                                    <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Fully Paid</option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Booking
                                    </button>
                                    <a href="{{ route('hotel-manager.bookings') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection