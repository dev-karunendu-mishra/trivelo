<x-mail::message>
# 🚫 Booking Cancellation Confirmed

Hello {{ $user->name }},

We've successfully processed your cancellation request for your upcoming stay at **{{ $hotel->name }}**.

## Cancelled Booking Details

<x-mail::panel>
**Confirmation Number:** #{{ $booking->booking_number }}<br>
**Cancelled On:** {{ now()->format('l, F j, Y \a\t g:i A') }}<br>
**Original Check-in:** {{ $booking->check_in->format('l, F j, Y') }}<br>
**Original Check-out:** {{ $booking->check_out->format('l, F j, Y') }}
</x-mail::panel>

## Hotel Information

**{{ $hotel->name }}**<br>
{{ $hotel->address }}<br>
{{ $hotel->city }}, {{ $hotel->state }} {{ $hotel->postal_code }}<br>
📞 {{ $hotel->phone }}

## Financial Information

<x-mail::table>
| Description | Amount |
|:------------|-------:|
| Original Booking Amount | ${{ number_format($booking->total_amount, 2) }} |
| @if($refundAmount > 0) Refund Amount @else Refund Amount (No refund due to policy) @endif | @if($refundAmount > 0) ${{ number_format($refundAmount, 2) }} @else $0.00 @endif |
</x-mail::table>

@if($refundAmount > 0)
## Refund Information

Your refund of **${{ number_format($refundAmount, 2) }}** will be processed back to your original payment method within 3-5 business days.

<x-mail::panel>
💡 **Note:** Refunds may take up to 3-5 business days to appear on your statement, depending on your bank or card issuer.
</x-mail::panel>
@else
## No Refund

According to the hotel's cancellation policy, this booking is not eligible for a refund. We understand this may be disappointing, and we appreciate your understanding.
@endif

## Need Help?

If you have any questions about this cancellation or need assistance with future bookings, please don't hesitate to contact us:

**{{ $hotel->name }}**<br>
📞 {{ $hotel->phone }}<br>
📧 {{ $hotel->email }}

We hope to welcome you back in the future!

<x-mail::button :url="route('search')" color="primary">
Find Another Hotel
</x-mail::button>

Thanks,<br>
The {{ $hotel->name }} Team<br>
{{ config('app.name') }}

---
<small>This is an automated cancellation confirmation. Please save this email for your records.</small>
</x-mail::message>
