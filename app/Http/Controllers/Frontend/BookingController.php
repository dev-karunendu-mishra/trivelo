<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Show room availability and pricing
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:6',
            'rooms' => 'required|integer|min:1|max:3',
        ]);

        $hotel = Hotel::with(['activeRooms.amenities'])->findOrFail($request->hotel_id);
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Get available rooms for the selected dates
        $availableRooms = $hotel->activeRooms()
            ->where('capacity', '>=', $request->guests)
            ->get()
            ->filter(function ($room) use ($checkIn, $checkOut) {
                return $room->isAvailableForDates($checkIn, $checkOut);
            });

        // Calculate pricing for each room
        $roomsWithPricing = $availableRooms->map(function ($room) use ($checkIn, $checkOut, $nights) {
            $totalPrice = 0;
            $currentDate = $checkIn->copy();
            
            while ($currentDate->lt($checkOut)) {
                $totalPrice += $room->getCurrentPrice($currentDate);
                $currentDate->addDay();
            }
            
            $room->total_price = $totalPrice;
            $room->avg_price_per_night = $totalPrice / $nights;
            
            return $room;
        });

        return view('frontend.booking.availability', compact(
            'hotel', 
            'roomsWithPricing', 
            'checkIn', 
            'checkOut', 
            'nights'
        ))->with([
            'searchParams' => $request->only(['check_in', 'check_out', 'guests', 'rooms'])
        ]);
    }

    /**
     * Show booking form for selected room
     */
    public function showBookingForm(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:6',
        ]);

        $hotel = Hotel::with(['location'])->findOrFail($request->hotel_id);
        $room = Room::with(['amenities'])->findOrFail($request->room_id);
        
        // Verify room belongs to hotel
        if ($room->hotel_id != $hotel->id) {
            abort(404);
        }

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);

        // Check availability again
        if (!$room->isAvailableForDates($checkIn, $checkOut)) {
            return redirect()->back()->with('error', 'Selected room is no longer available for these dates.');
        }

        // Calculate pricing
        $totalPrice = 0;
        $currentDate = $checkIn->copy();
        
        while ($currentDate->lt($checkOut)) {
            $totalPrice += $room->getCurrentPrice($currentDate);
            $currentDate->addDay();
        }

        // Calculate taxes and fees
        $subtotal = $totalPrice;
        $taxRate = 0.12; // 12% tax - adjust as needed
        $taxAmount = $subtotal * $taxRate;
        $totalAmount = $subtotal + $taxAmount;

        $booking = [
            'hotel' => $hotel,
            'room' => $room,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'nights' => $nights,
            'guests' => $request->guests,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ];

        return view('frontend.booking.form', compact('booking'));
    }

    /**
     * Process booking submission
     */
    public function submitBooking(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:6',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $hotel = Hotel::findOrFail($request->hotel_id);
        $room = Room::findOrFail($request->room_id);
        
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);

        // Final availability check
        if (!$room->isAvailableForDates($checkIn, $checkOut)) {
            return redirect()->back()->with('error', 'Selected room is no longer available for these dates.');
        }

        try {
            DB::beginTransaction();

            // Calculate pricing again
            $totalPrice = 0;
            $currentDate = $checkIn->copy();
            
            while ($currentDate->lt($checkOut)) {
                $totalPrice += $room->getCurrentPrice($currentDate);
                $currentDate->addDay();
            }

            $subtotal = $totalPrice;
            $taxAmount = $subtotal * 0.12; // 12% tax
            $totalAmount = $subtotal + $taxAmount;

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'hotel_id' => $hotel->id,
                'room_id' => $room->id,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'nights' => $nights,
                'adults' => $request->guests,
                'children' => 0, // Can be added later
                'room_rate' => $totalPrice / $nights,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_status' => 'pending',
                'special_requests' => $request->special_requests,
                'guest_details' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ],
            ]);

            DB::commit();

            // Redirect to payment form
            return redirect()->route('booking.payment', $booking->id)
                ->with('success', 'Booking details saved! Please complete payment to confirm your reservation.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'There was an error processing your booking. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show payment form for booking
     */
    public function showPaymentForm($bookingId)
    {
        $booking = Booking::with(['hotel.location', 'room', 'user'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if booking is in correct status for payment
        if ($booking->payment_status !== 'pending') {
            return redirect()->route('booking.confirmation', $booking->id)
                ->with('info', 'This booking has already been processed.');
        }

        return view('frontend.booking.payment', compact('booking'));
    }

    /**
     * Show booking confirmation page
     */
    public function confirmation($bookingId)
    {
        $booking = Booking::with(['hotel', 'room', 'user'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('frontend.booking.confirmation', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$booking->canBeCancelled()) {
            return redirect()->back()->with('error', 'This booking cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Calculate refund amount
            $refundAmount = $booking->calculateRefundAmount();
            $booking->refund_amount = $refundAmount;
            
            // Cancel booking
            $booking->cancel($request->cancellation_reason);

            // If there's a refund, it would be processed here
            // For now, we'll just update the booking status

            DB::commit();

            return redirect()->route('customer.bookings')
                ->with('success', 'Booking cancelled successfully.' . 
                    ($refundAmount > 0 ? " Refund of $" . number_format($refundAmount, 2) . " will be processed within 3-5 business days." : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', 'There was an error cancelling your booking. Please try again.');
        }
    }

    /**
     * Get booking details for AJAX requests
     */
    public function getBookingDetails($bookingId)
    {
        $booking = Booking::with(['hotel', 'room', 'user'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'booking' => $booking,
            'can_cancel' => $booking->canBeCancelled(),
            'refund_amount' => $booking->calculateRefundAmount(),
        ]);
    }
}
