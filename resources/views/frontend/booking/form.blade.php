@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'Complete Booking - ' . $booking['hotel']->name . ' - Trivelo')

@section('content')
    <!-- Booking Header -->
    <section class="booking-header bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('hotel.details', $booking['hotel']->id) }}">{{ $booking['hotel']->name }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('booking.availability', ['hotel_id' => $booking['hotel']->id]) }}">Availability</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Complete Booking</li>
                        </ol>
                    </nav>
                    
                    <!-- Progress Steps -->
                    <div class="booking-progress mb-4">
                        <div class="progress-steps">
                            <div class="step completed">
                                <div class="step-icon">✓</div>
                                <div class="step-label">Select Room</div>
                            </div>
                            <div class="step {{ request('step') === 'payment' ? 'active' : (request()->routeIs('booking.payment') ? '' : 'active') }}">
                                <div class="step-icon">2</div>
                                <div class="step-label">Guest Details</div>
                            </div>
                            <div class="step {{ request('step') === 'payment' || request()->routeIs('booking.payment') ? 'active' : '' }}">
                                <div class="step-icon">3</div>
                                <div class="step-label">Payment</div>
                            </div>
                            <div class="step">
                                <div class="step-icon">4</div>
                                <div class="step-label">Confirmation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form -->
    <section class="booking-form-section py-5">
        <div class="container">
            <div class="row">
                <!-- Main Form -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">Guest Information</h4>
                            <small class="text-muted">Please provide your details to complete the booking</small>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('booking.submit') }}" method="POST" id="bookingSubmitForm">
                                @csrf
                                <input type="hidden" name="hotel_id" value="{{ $booking['hotel']->id }}">
                                <input type="hidden" name="room_id" value="{{ $booking['room']->id }}">
                                <input type="hidden" name="check_in" value="{{ $booking['check_in']->format('Y-m-d') }}">
                                <input type="hidden" name="check_out" value="{{ $booking['check_out']->format('Y-m-d') }}">
                                <input type="hidden" name="guests" value="{{ $booking['guests'] }}">
                                
                                <!-- Personal Information -->
                                <div class="form-section mb-4">
                                    <h5 class="form-section-title">Personal Information</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                   id="first_name" name="first_name" 
                                                   value="{{ old('first_name', auth()->user()->name ?? '') }}" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                   id="last_name" name="last_name" 
                                                   value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" 
                                                   value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" 
                                                   value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Special Requests -->
                                <div class="form-section mb-4">
                                    <h5 class="form-section-title">Special Requests</h5>
                                    <div class="mb-3">
                                        <label for="special_requests" class="form-label">Special Requests (Optional)</label>
                                        <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                                  id="special_requests" name="special_requests" rows="4"
                                                  placeholder="Any special requirements, dietary needs, accessibility requests, etc.">{{ old('special_requests') }}</textarea>
                                        @error('special_requests')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Examples: Late check-in, wheelchair accessibility, dietary restrictions, celebration arrangements</div>
                                    </div>
                                </div>
                                
                                <!-- Terms and Conditions -->
                                <div class="form-section mb-4">
                                    <h5 class="form-section-title">Terms & Conditions</h5>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input @error('terms_accepted') is-invalid @enderror" 
                                               type="checkbox" id="terms_accepted" name="terms_accepted" value="1" 
                                               {{ old('terms_accepted') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="terms_accepted">
                                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a> 
                                            and <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a> <span class="text-danger">*</span>
                                        </label>
                                        @error('terms_accepted')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="marketing_emails" name="marketing_emails" value="1" 
                                               {{ old('marketing_emails') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="marketing_emails">
                                            I would like to receive special offers and updates via email
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('booking.availability', ['hotel_id' => $booking['hotel']->id]) }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Back to Room Selection
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBookingBtn">
                                        <span class="btn-text">Complete Booking</span>
                                        <span class="btn-spinner d-none">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            Processing...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Summary Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 position-sticky" style="top: 100px;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Booking Summary</h5>
                        </div>
                        <div class="card-body">
                            <!-- Hotel Info -->
                            <div class="booking-summary-section mb-4">
                                <h6 class="fw-bold">{{ $booking['hotel']->name }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt"></i> {{ $booking['hotel']->location->city ?? $booking['hotel']->city }}
                                </p>
                                <div class="star-rating">
                                    @for($i = 0; $i < $booking['hotel']->star_rating; $i++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                            
                            <!-- Room Info -->
                            <div class="booking-summary-section mb-4">
                                <h6 class="fw-bold">{{ $booking['room']->type }}</h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-people"></i> {{ $booking['guests'] }} guest{{ $booking['guests'] != 1 ? 's' : '' }}
                                </p>
                            </div>
                            
                            <!-- Dates -->
                            <div class="booking-summary-section mb-4">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Check-in</strong><br>
                                        <span class="text-muted">{{ $booking['check_in']->format('M d, Y') }}</span><br>
                                        <small class="text-muted">After 3:00 PM</small>
                                    </div>
                                    <div class="col-6">
                                        <strong>Check-out</strong><br>
                                        <span class="text-muted">{{ $booking['check_out']->format('M d, Y') }}</span><br>
                                        <small class="text-muted">Before 11:00 AM</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <strong>{{ $booking['nights'] }} night{{ $booking['nights'] != 1 ? 's' : '' }}</strong>
                                </div>
                            </div>
                            
                            <!-- Price Breakdown -->
                            <div class="booking-summary-section">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Room rate ({{ $booking['nights'] }} night{{ $booking['nights'] != 1 ? 's' : '' }})</span>
                                    <span>${{ number_format($booking['subtotal'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Taxes & fees</span>
                                    <span>${{ number_format($booking['tax_amount'], 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-0">
                                    <strong>Total</strong>
                                    <strong class="text-primary">${{ number_format($booking['total_amount'], 2) }}</strong>
                                </div>
                                <small class="text-muted">Including all taxes and fees</small>
                            </div>
                        </div>
                        
                        <!-- Security & Policy Info -->
                        <div class="card-footer bg-light">
                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="bi bi-shield-check text-success"></i><br>
                                    <small>Secure</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-calendar-x text-info"></i><br>
                                    <small>Free Cancellation</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-headset text-primary"></i><br>
                                    <small>24/7 Support</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms of Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Booking Terms</h6>
                    <p>By completing this booking, you agree to the following terms:</p>
                    <ul>
                        <li>Payment is required to confirm your reservation</li>
                        <li>Cancellation must be made at least 24 hours before check-in for full refund</li>
                        <li>Check-in time is 3:00 PM, check-out is 11:00 AM</li>
                        <li>Valid ID is required at check-in</li>
                        <li>Hotel policies apply regarding smoking, pets, and noise</li>
                    </ul>
                    
                    <h6>Payment Terms</h6>
                    <p>Your payment information is processed securely. By proceeding, you authorize the charge to your payment method.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Information Collection</h6>
                    <p>We collect information necessary to process your booking including:</p>
                    <ul>
                        <li>Personal contact information</li>
                        <li>Payment details (processed securely)</li>
                        <li>Special requests and preferences</li>
                    </ul>
                    
                    <h6>Data Protection</h6>
                    <p>Your personal information is protected and will not be shared with third parties except as necessary to complete your booking.</p>
                    
                    <h6>Marketing Communications</h6>
                    <p>You may opt-in to receive promotional emails. You can unsubscribe at any time.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .booking-progress {
        margin-bottom: 2rem;
    }
    
    .progress-steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin: 0 auto;
        max-width: 600px;
    }
    
    .progress-steps::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 50px;
        right: 50px;
        height: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    
    .step {
        position: relative;
        text-align: center;
        z-index: 2;
    }
    
    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 8px;
        transition: all 0.3s ease;
    }
    
    .step.completed .step-icon {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }
    
    .step.active .step-icon {
        background: #007bff;
        border-color: #007bff;
        color: white;
    }
    
    .step-label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .step.active .step-label,
    .step.completed .step-label {
        color: #495057;
        font-weight: 600;
    }
    
    .form-section {
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1.5rem;
    }
    
    .form-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .form-section-title {
        color: #495057;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        border-left: 3px solid #007bff;
        padding-left: 10px;
    }
    
    .booking-summary-section {
        padding-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .booking-summary-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .btn-spinner {
        display: none;
    }
    
    .submitting .btn-text {
        display: none;
    }
    
    .submitting .btn-spinner {
        display: inline;
    }
    
    @media (max-width: 768px) {
        .progress-steps {
            max-width: 100%;
        }
        
        .progress-steps::before {
            left: 25px;
            right: 25px;
        }
        
        .step-icon {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }
        
        .step-label {
            font-size: 0.8rem;
        }
        
        .position-sticky {
            position: static !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingSubmitForm');
    const submitBtn = document.getElementById('submitBookingBtn');
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.classList.add('submitting');
        submitBtn.disabled = true;
        
        // Client-side validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            submitBtn.classList.remove('submitting');
            submitBtn.disabled = false;
            
            // Scroll to first error
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }
    
    // Phone validation
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            // Remove non-numeric characters for validation
            const numericValue = this.value.replace(/\D/g, '');
            if (numericValue.length >= 10) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }
});
</script>
@endpush