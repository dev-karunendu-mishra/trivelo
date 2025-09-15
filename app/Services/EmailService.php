<?php

namespace App\Services;

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Mail\WelcomeMail;
use App\Mail\BookingConfirmationMail;
use App\Mail\BookingCancellationMail;
use App\Mail\BookingReminderMail;
use App\Mail\PaymentReceiptMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
            
            Log::info('Welcome email sent', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send booking confirmation email
     */
    public function sendBookingConfirmation(Booking $booking): bool
    {
        try {
            Mail::to($booking->user->email)->send(new BookingConfirmationMail($booking));
            
            Log::info('Booking confirmation email sent', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'email' => $booking->user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send booking cancellation email
     */
    public function sendBookingCancellation(Booking $booking, $refundAmount = null): bool
    {
        try {
            Mail::to($booking->user->email)->send(new BookingCancellationMail($booking, $refundAmount));
            
            Log::info('Booking cancellation email sent', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'email' => $booking->user->email,
                'refund_amount' => $refundAmount
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send booking cancellation email', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send booking reminder email
     */
    public function sendBookingReminder(Booking $booking, int $daysUntilCheckIn): bool
    {
        try {
            Mail::to($booking->user->email)->send(new BookingReminderMail($booking, $daysUntilCheckIn));
            
            Log::info('Booking reminder email sent', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'email' => $booking->user->email,
                'days_until_checkin' => $daysUntilCheckIn
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send booking reminder email', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send payment receipt email
     */
    public function sendPaymentReceipt(Payment $payment, Booking $booking): bool
    {
        try {
            Mail::to($booking->user->email)->send(new PaymentReceiptMail($payment, $booking));
            
            Log::info('Payment receipt email sent', [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'email' => $booking->user->email,
                'amount' => $payment->amount
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment receipt email', [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send all booking reminder emails for today
     */
    public function sendTodaysReminders(): int
    {
        $sentCount = 0;
        
        // Send reminders for bookings that are 1 day away
        $tomorrowBookings = Booking::with(['user', 'hotel', 'room'])
            ->where('check_in_date', now()->addDay()->toDateString())
            ->where('status', 'confirmed')
            ->get();

        foreach ($tomorrowBookings as $booking) {
            if ($this->sendBookingReminder($booking, 1)) {
                $sentCount++;
            }
        }

        // Send reminders for bookings that are 3 days away
        $threeDayBookings = Booking::with(['user', 'hotel', 'room'])
            ->where('check_in_date', now()->addDays(3)->toDateString())
            ->where('status', 'confirmed')
            ->get();

        foreach ($threeDayBookings as $booking) {
            if ($this->sendBookingReminder($booking, 3)) {
                $sentCount++;
            }
        }

        // Send reminders for bookings that are 7 days away
        $weekBookings = Booking::with(['user', 'hotel', 'room'])
            ->where('check_in_date', now()->addDays(7)->toDateString())
            ->where('status', 'confirmed')
            ->get();

        foreach ($weekBookings as $booking) {
            if ($this->sendBookingReminder($booking, 7)) {
                $sentCount++;
            }
        }

        Log::info('Daily booking reminders sent', ['count' => $sentCount]);
        
        return $sentCount;
    }
}