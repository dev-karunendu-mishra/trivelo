@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'Payment - ' . $booking->hotel->name . ' - Trivelo')

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
                            <li class="breadcrumb-item"><a href="{{ route('hotel.details', $booking->hotel->id) }}">{{ $booking->hotel->name }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('booking.availability', ['hotel_id' => $booking->hotel->id]) }}">Availability</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payment</li>
                        </ol>
                    </nav>
                    
                    <!-- Progress Steps -->
                    <div class="booking-progress mb-4">
                        <div class="progress-steps">
                            <div class="step completed">
                                <div class="step-icon">✓</div>
                                <div class="step-label">Select Room</div>
                            </div>
                            <div class="step completed">
                                <div class="step-icon">✓</div>
                                <div class="step-label">Guest Details</div>
                            </div>
                            <div class="step active">
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

    <!-- Payment Form -->
    <section class="payment-section py-5">
        <div class="container">
            <div class="row">
                <!-- Payment Form -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">Payment Information</h4>
                            <small class="text-muted">Complete your booking with secure payment</small>
                        </div>
                        <div class="card-body">
                            <!-- Payment Method Selection -->
                            <div class="payment-methods mb-4">
                                <h5 class="form-section-title">Payment Method</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="payment-method-option active" data-method="stripe">
                                            <div class="payment-method-header">
                                                <input type="radio" name="payment_method" id="stripe" value="stripe" checked>
                                                <label for="stripe">
                                                    <i class="bi bi-credit-card"></i>
                                                    Credit/Debit Card
                                                </label>
                                            </div>
                                            <div class="payment-method-logos">
                                                <span class="payment-logo visa">VISA</span>
                                                <span class="payment-logo mastercard">MC</span>
                                                <span class="payment-logo amex">AMEX</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="payment-method-option" data-method="paypal">
                                            <div class="payment-method-header">
                                                <input type="radio" name="payment_method" id="paypal" value="paypal">
                                                <label for="paypal">
                                                    <i class="bi bi-paypal"></i>
                                                    PayPal
                                                </label>
                                            </div>
                                            <div class="payment-method-logos">
                                                <span class="payment-logo paypal">PayPal</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stripe Payment Form -->
                            <div id="stripe-payment-form" class="payment-form-content">
                                <form id="payment-form">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="cardholder-name" class="form-label">Cardholder Name</label>
                                            <input type="text" id="cardholder-name" class="form-control" 
                                                   value="{{ $booking->guest_name }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cardholder-email" class="form-label">Email Address</label>
                                            <input type="email" id="cardholder-email" class="form-control" 
                                                   value="{{ $booking->guest_email }}" required>
                                        </div>
                                    </div>

                                    <!-- Stripe Elements Container -->
                                    <div class="mb-3">
                                        <label class="form-label">Card Information</label>
                                        <div id="card-element" class="form-control" style="height: 40px; padding: 10px;">
                                            <!-- Stripe Elements will create form elements here -->
                                        </div>
                                        <div id="card-errors" role="alert" class="text-danger small mt-1"></div>
                                    </div>

                                    <!-- Billing Address -->
                                    <div class="billing-address mb-4">
                                        <h6 class="mb-3">Billing Address</h6>
                                        <div class="row">
                                            <div class="col-md-8 mb-3">
                                                <label for="address_line_1" class="form-label">Address Line 1</label>
                                                <input type="text" id="address_line_1" class="form-control" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="postal_code" class="form-label">Postal Code</label>
                                                <input type="text" id="postal_code" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" id="city" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <select id="country" class="form-select" required>
                                                    <option value="US">United States</option>
                                                    <option value="CA">Canada</option>
                                                    <option value="GB">United Kingdom</option>
                                                    <option value="AU">Australia</option>
                                                    <option value="DE">Germany</option>
                                                    <option value="FR">France</option>
                                                    <option value="IT">Italy</option>
                                                    <option value="ES">Spain</option>
                                                    <option value="JP">Japan</option>
                                                    <option value="IN">India</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Security Info -->
                                    <div class="payment-security mb-4">
                                        <div class="alert alert-info">
                                            <i class="bi bi-shield-check"></i>
                                            <strong>Secure Payment:</strong> Your payment information is encrypted and secure. 
                                            We use industry-standard SSL encryption to protect your data.
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('booking.form', ['booking_id' => $booking->id]) }}" 
                                           class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left"></i> Back to Guest Details
                                        </a>
                                        <button type="submit" id="submit-payment" class="btn btn-primary btn-lg">
                                            <span class="btn-text">
                                                <i class="bi bi-lock"></i> Pay ${{ number_format($booking->total_amount, 2) }}
                                            </span>
                                            <span class="btn-spinner d-none">
                                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                Processing Payment...
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- PayPal Payment Form -->
                            <div id="paypal-payment-form" class="payment-form-content d-none">
                                <div class="text-center py-4">
                                    <div id="paypal-button-container"></div>
                                    <p class="text-muted mt-3">You will be redirected to PayPal to complete your payment securely.</p>
                                </div>
                            </div>
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
                                <h6 class="fw-bold">{{ $booking->hotel->name }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt"></i> {{ $booking->hotel->location->city ?? $booking->hotel->city }}
                                </p>
                                <div class="star-rating">
                                    @for($i = 0; $i < $booking->hotel->star_rating; $i++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                            
                            <!-- Guest Info -->
                            <div class="booking-summary-section mb-4">
                                <h6 class="fw-bold">Guest Information</h6>
                                <p class="mb-1">{{ $booking->guest_name }}</p>
                                <p class="text-muted small mb-0">{{ $booking->guest_email }}</p>
                            </div>
                            
                            <!-- Room Info -->
                            <div class="booking-summary-section mb-4">
                                <h6 class="fw-bold">{{ $booking->room->type }}</h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-people"></i> {{ $booking->number_of_guests }} guest{{ $booking->number_of_guests != 1 ? 's' : '' }}
                                </p>
                            </div>
                            
                            <!-- Dates -->
                            <div class="booking-summary-section mb-4">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Check-in</strong><br>
                                        <span class="text-muted">{{ $booking->check_in_date->format('M d, Y') }}</span><br>
                                        <small class="text-muted">After 3:00 PM</small>
                                    </div>
                                    <div class="col-6">
                                        <strong>Check-out</strong><br>
                                        <span class="text-muted">{{ $booking->check_out_date->format('M d, Y') }}</span><br>
                                        <small class="text-muted">Before 11:00 AM</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <strong>{{ $booking->nights }} night{{ $booking->nights != 1 ? 's' : '' }}</strong>
                                </div>
                            </div>
                            
                            <!-- Price Breakdown -->
                            <div class="booking-summary-section">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Room rate ({{ $booking->nights }} night{{ $booking->nights != 1 ? 's' : '' }})</span>
                                    <span>${{ number_format($booking->subtotal_amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Taxes & fees</span>
                                    <span>${{ number_format($booking->tax_amount, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-0">
                                    <strong>Total</strong>
                                    <strong class="text-primary">${{ number_format($booking->total_amount, 2) }}</strong>
                                </div>
                                <small class="text-muted">Including all taxes and fees</small>
                            </div>
                        </div>
                        
                        <!-- Security & Policy Info -->
                        <div class="card-footer bg-light">
                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="bi bi-shield-check text-success"></i><br>
                                    <small>SSL Secured</small>
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

    .payment-method-option {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method-option:hover {
        border-color: #007bff;
    }

    .payment-method-option.active {
        border-color: #007bff;
        background-color: #f8f9ff;
    }

    .payment-method-header {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .payment-method-header input[type="radio"] {
        margin-right: 0.5rem;
    }

    .payment-method-header label {
        font-weight: 600;
        margin: 0;
        cursor: pointer;
    }

    .payment-method-header i {
        margin-right: 0.5rem;
        font-size: 1.2rem;
    }

    .payment-method-logos {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .payment-logo {
        display: inline-block;
        padding: 4px 8px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #fff;
        text-align: center;
        min-width: 50px;
    }

    .payment-logo.visa {
        background: #1a1f71;
    }

    .payment-logo.mastercard {
        background: #eb001b;
    }

    .payment-logo.amex {
        background: #006fcf;
    }

    .payment-logo.paypal {
        background: #0070ba;
    }

    .form-section-title {
        color: #495057;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        border-left: 3px solid #007bff;
        padding-left: 10px;
    }

    #card-element {
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: white;
    }

    #card-element.StripeElement--focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    #card-element.StripeElement--invalid {
        border-color: #dc3545;
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
    
    .processing .btn-text {
        display: none;
    }
    
    .processing .btn-spinner {
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
<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Stripe
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();

    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });

    cardElement.mount('#card-element');

    // Handle real-time validation errors from the card Element
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Payment method selection
    const paymentMethodOptions = document.querySelectorAll('.payment-method-option');
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
    
    paymentMethodOptions.forEach(option => {
        option.addEventListener('click', function() {
            const method = this.dataset.method;
            const input = this.querySelector('input[type="radio"]');
            
            // Update UI
            paymentMethodOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            input.checked = true;
            
            // Show/hide payment forms
            if (method === 'stripe') {
                document.getElementById('stripe-payment-form').classList.remove('d-none');
                document.getElementById('paypal-payment-form').classList.add('d-none');
            } else if (method === 'paypal') {
                document.getElementById('stripe-payment-form').classList.add('d-none');
                document.getElementById('paypal-payment-form').classList.remove('d-none');
                initializePayPal();
            }
        });
    });

    // Handle Stripe form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-payment');

    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        // Show loading state
        submitButton.classList.add('processing');
        submitButton.disabled = true;

        try {
            // Create payment intent
            const response = await fetch(`/api/bookings/{{ $booking->id }}/payment-intent`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const { success, client_secret, error } = await response.json();

            if (!success) {
                throw new Error(error || 'Failed to create payment intent');
            }

            // Confirm payment with Stripe
            const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(client_secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: document.getElementById('cardholder-name').value,
                        email: document.getElementById('cardholder-email').value,
                        address: {
                            line1: document.getElementById('address_line_1').value,
                            city: document.getElementById('city').value,
                            postal_code: document.getElementById('postal_code').value,
                            country: document.getElementById('country').value,
                        }
                    }
                }
            });

            if (stripeError) {
                // Show error to customer
                document.getElementById('card-errors').textContent = stripeError.message;
                
                // Log payment failure
                await fetch('/api/payments/failed', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent?.id || client_secret.split('_secret')[0],
                        booking_id: {{ $booking->id }},
                        error_message: stripeError.message
                    })
                });
            } else {
                // Payment succeeded
                const confirmResponse = await fetch('/api/payments/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id,
                        booking_id: {{ $booking->id }}
                    })
                });

                const confirmResult = await confirmResponse.json();

                if (confirmResult.success) {
                    // Redirect to confirmation page
                    window.location.href = confirmResult.redirect_url;
                } else {
                    throw new Error(confirmResult.error || 'Payment confirmation failed');
                }
            }
        } catch (error) {
            console.error('Payment error:', error);
            document.getElementById('card-errors').textContent = error.message || 'An unexpected error occurred.';
        } finally {
            // Reset loading state
            submitButton.classList.remove('processing');
            submitButton.disabled = false;
        }
    });

    // Initialize PayPal (placeholder - will be implemented when PayPal integration is added)
    function initializePayPal() {
        // PayPal SDK initialization will go here
        console.log('PayPal initialization placeholder');
    }
});
</script>
@endpush