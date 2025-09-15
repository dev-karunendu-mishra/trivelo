<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create payment intent for a booking
     */
    public function createPaymentIntent(Request $request, Booking $booking): JsonResponse
    {
        try {
            // Validate that the booking belongs to the authenticated user or is accessible
            if (Auth::check() && $booking->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized access to this booking',
                ], 403);
            }

            // Check if booking is in correct status for payment
            if ($booking->payment_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'error' => 'This booking is not available for payment',
                ], 400);
            }

            $result = $this->paymentService->createStripePaymentIntent($booking);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'client_secret' => $result['client_secret'],
                    'payment_intent_id' => $result['payment_intent_id'],
                    'amount' => $booking->total_amount,
                    'currency' => 'USD',
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to create payment intent',
            ], 500);

        } catch (\Exception $e) {
            Log::error('Payment intent creation failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing your request',
            ], 500);
        }
    }

    /**
     * Confirm payment after successful Stripe payment
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'booking_id' => 'required|exists:bookings,id',
        ]);

        try {
            DB::beginTransaction();

            $result = $this->paymentService->confirmStripePayment(
                $request->payment_intent_id,
                $request->booking_id
            );

            if ($result['success']) {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'booking' => $result['booking'],
                    'payment' => $result['payment'],
                    'redirect_url' => route('bookings.confirmation', $result['booking']->id),
                ]);
            }

            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Payment confirmation failed',
            ], 400);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment confirmation failed', [
                'payment_intent_id' => $request->payment_intent_id,
                'booking_id' => $request->booking_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while confirming your payment',
            ], 500);
        }
    }

    /**
     * Handle payment failure
     */
    public function paymentFailed(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'booking_id' => 'required|exists:bookings,id',
            'error_message' => 'sometimes|string',
        ]);

        try {
            $booking = Booking::findOrFail($request->booking_id);
            $payment = Payment::where('payment_intent_id', $request->payment_intent_id)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'payment_data' => array_merge($payment->payment_data ?? [], [
                        'error_message' => $request->error_message,
                        'failed_at' => now(),
                    ]),
                ]);
            }

            // Update booking status
            $booking->update([
                'payment_status' => 'failed',
                'booking_status' => 'payment_failed',
            ]);

            Log::warning('Payment failed', [
                'booking_id' => $booking->id,
                'payment_intent_id' => $request->payment_intent_id,
                'error_message' => $request->error_message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment failure recorded',
                'retry_url' => route('bookings.payment', $booking->id),
            ]);

        } catch (\Exception $e) {
            Log::error('Payment failure handling error', [
                'payment_intent_id' => $request->payment_intent_id,
                'booking_id' => $request->booking_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing payment failure',
            ], 500);
        }
    }

    /**
     * Process refund for a booking
     */
    public function processRefund(Request $request, Booking $booking): JsonResponse
    {
        $request->validate([
            'amount' => 'sometimes|numeric|min:1',
            'reason' => 'sometimes|string|max:500',
        ]);

        try {
            // Check authorization
            if (!Auth::user()->hasRole(['super-admin', 'hotel-manager'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized to process refunds',
                ], 403);
            }

            $payment = $booking->payments()->where('status', 'completed')->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'error' => 'No completed payment found for this booking',
                ], 400);
            }

            $result = $this->paymentService->processRefund(
                $payment,
                $request->amount
            );

            if ($result['success']) {
                // Update booking status if full refund
                if (!$request->amount || $request->amount >= $payment->amount) {
                    $booking->update([
                        'payment_status' => 'refunded',
                        'booking_status' => 'cancelled',
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'refund' => $result['refund'],
                    'payment' => $result['payment'],
                    'message' => 'Refund processed successfully',
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Refund processing failed',
            ], 400);

        } catch (\Exception $e) {
            Log::error('Refund processing error', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the refund',
            ], 500);
        }
    }

    /**
     * Get payment status for a booking
     */
    public function getPaymentStatus(Booking $booking): JsonResponse
    {
        try {
            $payment = $booking->payments()->latest()->first();

            return response()->json([
                'success' => true,
                'booking_status' => $booking->booking_status,
                'payment_status' => $booking->payment_status,
                'payment' => $payment ? [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'status' => $payment->status,
                    'payment_method' => $payment->payment_method,
                    'created_at' => $payment->created_at,
                ] : null,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status retrieval error', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve payment status',
            ], 500);
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );

            Log::info('Stripe webhook received', ['type' => $event->type]);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event->data->object);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event->data->object);
                    break;

                case 'charge.dispute.created':
                    $this->handleChargeDispute($event->data->object);
                    break;

                default:
                    Log::info('Unhandled webhook event type', ['type' => $event->type]);
            }

            return response()->json(['status' => 'success']);

        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload in webhook', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature in webhook', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);

        } catch (\Exception $e) {
            Log::error('Webhook processing error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle successful payment intent webhook
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();

        if ($payment && $payment->status !== 'completed') {
            $booking = $payment->booking;

            $payment->update([
                'status' => 'completed',
                'payment_data' => array_merge($payment->payment_data ?? [], [
                    'webhook_processed_at' => now(),
                    'payment_method_details' => $paymentIntent->payment_method,
                ]),
            ]);

            $booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
            ]);

            Log::info('Payment confirmed via webhook', [
                'booking_id' => $booking->id,
                'payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * Handle failed payment intent webhook
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();

        if ($payment) {
            $booking = $payment->booking;

            $payment->update([
                'status' => 'failed',
                'payment_data' => array_merge($payment->payment_data ?? [], [
                    'webhook_processed_at' => now(),
                    'failure_reason' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
                ]),
            ]);

            $booking->update([
                'payment_status' => 'failed',
                'booking_status' => 'payment_failed',
            ]);

            Log::warning('Payment failed via webhook', [
                'booking_id' => $booking->id,
                'payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * Handle charge dispute webhook
     */
    protected function handleChargeDispute($dispute)
    {
        Log::warning('Charge dispute created', [
            'dispute_id' => $dispute->id,
            'charge_id' => $dispute->charge,
            'amount' => $dispute->amount,
            'reason' => $dispute->reason,
        ]);

        // Additional dispute handling logic can be added here
    }
}