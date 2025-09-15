<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    protected $stripeSecretKey;

    public function __construct()
    {
        $this->stripeSecretKey = config('services.stripe.secret');
        Stripe::setApiKey($this->stripeSecretKey);
    }

    /**
     * Create a payment intent for Stripe
     */
    public function createStripePaymentIntent(Booking $booking)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToStripeAmount($booking->total_amount),
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'booking_id' => $booking->id,
                    'hotel_name' => $booking->hotel->name,
                    'guest_name' => $booking->guest_name,
                    'guest_email' => $booking->guest_email,
                ],
                'description' => "Hotel booking at {$booking->hotel->name} from {$booking->check_in_date} to {$booking->check_out_date}",
            ]);

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'stripe',
                'amount' => $booking->total_amount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_intent_id' => $paymentIntent->id,
                'payment_data' => [
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                ],
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];

        } catch (Exception $e) {
            Log::error('Stripe payment intent creation failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm payment and update booking status
     */
    public function confirmStripePayment($paymentIntentId, $bookingId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $booking = Booking::findOrFail($bookingId);
            $payment = Payment::where('payment_intent_id', $paymentIntentId)->first();

            if ($paymentIntent->status === 'succeeded') {
                // Update payment status
                $payment->update([
                    'status' => 'completed',
                    'payment_data' => array_merge($payment->payment_data ?? [], [
                        'payment_method_details' => $paymentIntent->payment_method,
                        'receipt_url' => $paymentIntent->charges->data[0]->receipt_url ?? null,
                        'payment_completed_at' => now(),
                    ]),
                ]);

                // Update booking status
                $booking->update([
                    'payment_status' => 'paid',
                    'booking_status' => 'confirmed',
                ]);

                // Send confirmation email
                $this->sendBookingConfirmationEmail($booking);

                return [
                    'success' => true,
                    'booking' => $booking,
                    'payment' => $payment,
                ];
            }

            return [
                'success' => false,
                'error' => 'Payment was not successful',
            ];

        } catch (Exception $e) {
            Log::error('Stripe payment confirmation failed', [
                'payment_intent_id' => $paymentIntentId,
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process refund for a payment
     */
    public function processRefund(Payment $payment, $amount = null)
    {
        try {
            if ($payment->payment_method === 'stripe') {
                return $this->processStripeRefund($payment, $amount);
            }

            return [
                'success' => false,
                'error' => 'Unsupported payment method for refund',
            ];

        } catch (Exception $e) {
            Log::error('Refund processing failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process Stripe refund
     */
    protected function processStripeRefund(Payment $payment, $amount = null)
    {
        try {
            $refundData = [
                'payment_intent' => $payment->payment_intent_id,
            ];

            if ($amount) {
                $refundData['amount'] = $this->convertToStripeAmount($amount);
            }

            $refund = \Stripe\Refund::create($refundData);

            // Update payment record
            $payment->update([
                'status' => $refund->status === 'succeeded' ? 'refunded' : 'refund_pending',
                'payment_data' => array_merge($payment->payment_data ?? [], [
                    'refund_id' => $refund->id,
                    'refund_amount' => $amount ?? $payment->amount,
                    'refund_status' => $refund->status,
                    'refunded_at' => now(),
                ]),
            ]);

            return [
                'success' => true,
                'refund' => $refund,
                'payment' => $payment,
            ];

        } catch (Exception $e) {
            Log::error('Stripe refund processing failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Convert amount to Stripe format (cents)
     */
    protected function convertToStripeAmount($amount)
    {
        return intval($amount * 100);
    }

    /**
     * Convert amount from Stripe format (cents to dollars)
     */
    protected function convertFromStripeAmount($amount)
    {
        return $amount / 100;
    }

    /**
     * Validate payment method
     */
    public function validatePaymentMethod($paymentMethodId)
    {
        try {
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
            return [
                'success' => true,
                'payment_method' => $paymentMethod,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send booking confirmation email
     */
    protected function sendBookingConfirmationEmail(Booking $booking)
    {
        // This will be implemented when we create the email notification system
        // For now, we'll log it
        Log::info('Booking confirmation email should be sent', [
            'booking_id' => $booking->id,
            'guest_email' => $booking->guest_email,
        ]);
    }

    /**
     * Get payment methods for a customer
     */
    public function getCustomerPaymentMethods($customerId)
    {
        try {
            $paymentMethods = PaymentMethod::all([
                'customer' => $customerId,
                'type' => 'card',
            ]);

            return [
                'success' => true,
                'payment_methods' => $paymentMethods->data,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create or get Stripe customer
     */
    public function getOrCreateStripeCustomer($email, $name = null)
    {
        try {
            // Search for existing customer
            $customers = \Stripe\Customer::all([
                'email' => $email,
                'limit' => 1,
            ]);

            if (count($customers->data) > 0) {
                return [
                    'success' => true,
                    'customer' => $customers->data[0],
                ];
            }

            // Create new customer
            $customer = \Stripe\Customer::create([
                'email' => $email,
                'name' => $name,
            ]);

            return [
                'success' => true,
                'customer' => $customer,
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}