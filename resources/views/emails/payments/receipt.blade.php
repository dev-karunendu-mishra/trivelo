<x-mail::message>
# Payment Receipt 💳

Hello {{ $user->name }},

Thank you for your payment! This email serves as your official receipt for the recent transaction.

<x-mail::panel>
**Payment Status: COMPLETED ✅**

Your payment has been successfully processed and your booking is confirmed.
</x-mail::panel>

## Payment Details

**Receipt #:** {{ $payment->payment_intent_id ?? $payment->id }}
**Payment Date:** {{ $payment->created_at->format('F j, Y \a\t g:i A') }}
**Payment Method:** {{ ucfirst($payment->payment_method ?? 'Card') }}
**Transaction ID:** {{ $payment->transaction_id ?? $payment->payment_intent_id }}

## Booking Information

**Booking Reference:** #{{ $booking->booking_number }}
**Hotel:** {{ $hotel->name }}
**Room Type:** {{ $room->type }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
**Duration:** {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} night{{ $booking->check_in_date->diffInDays($booking->check_out_date) > 1 ? 's' : '' }}
**Guests:** {{ $booking->guests }}

## Payment Breakdown

<x-mail::table>
| Description | Amount |
|:------------|-------:|
| Room Rate ({{ $booking->check_in_date->diffInDays($booking->check_out_date) }} nights) | ${{ number_format($booking->room_rate, 2) }} |
| Taxes & Fees | ${{ number_format($booking->tax_amount ?? 0, 2) }} |
| **Total Paid** | **${{ number_format($payment->amount, 2) }}** |
</x-mail::table>

@if($payment->status === 'completed')
<x-mail::panel>
**✅ Payment Confirmed**

Your payment of **${{ number_format($payment->amount, 2) }}** has been successfully charged to your {{ ucfirst($payment->payment_method ?? 'card') }}.
</x-mail::panel>
@endif

## Hotel Information

**{{ $hotel->name }}**
{{ $hotel->address }}
{{ $hotel->city }}, {{ $hotel->state }} {{ $hotel->zip_code }}

@if($hotel->phone)
**Phone:** {{ $hotel->phone }}
@endif

@if($hotel->email)
**Email:** {{ $hotel->email }}
@endif

## Important Notes

- **Cancellation Policy:** Please refer to your booking confirmation for cancellation terms
- **Check-in:** Valid photo ID required at check-in
- **Incidentals:** Additional charges may apply for room service, minibar, etc.
- **Receipt:** Keep this email for your records and tax purposes

<x-mail::button :url="route('customer.bookings.show', $booking->id)">
View Full Booking Details
</x-mail::button>

## Need Help?

If you have any questions about this payment or your booking, please don't hesitate to contact us:

- **Email Support:** {{ config('mail.support_email', 'support@trivelo.com') }}
- **Phone Support:** Available 24/7
- **Live Chat:** Available on our website

<x-mail::button :url="'mailto:' . config('mail.support_email', 'support@trivelo.com')">
Contact Support
</x-mail::button>

## Refund Policy

If you need to cancel your booking, refunds will be processed according to the hotel's cancellation policy. Please allow 3-5 business days for refunds to appear on your original payment method.

Thank you for choosing {{ config('app.name') }} for your travel needs!

Best regards,<br>
{{ config('app.name') }} Team

---

**Billing Questions?**
This charge will appear on your statement as "{{ config('app.name') }}" or "TRIVELO BOOKING"

*This is an automated receipt. Please do not reply to this email. For support, use the contact methods above.*
</x-mail::message>
