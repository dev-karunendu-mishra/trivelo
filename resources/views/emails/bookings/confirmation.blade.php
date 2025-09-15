<x-mail::message>
# 🎉 Booking Confirmed!

Hello {{ $user->name }},

Great news! Your booking has been confirmed. We're excited to welcome you to **{{ $hotel->name }}**.

## Booking Details

<x-mail::panel>
**Confirmation Number:** #{{ $booking->booking_number }}<br>
**Guest Name:** {{ $user->name }}<br>
**Email:** {{ $user->email }}<br>
**Phone:** {{ $user->phone ?? 'Not provided' }}
</x-mail::panel>

## Stay Information

**Hotel:** {{ $hotel->name }}<br>
**Address:** {{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->state }} {{ $hotel->postal_code }}<br>
**Room:** {{ $room->name }} ({{ $room->room_number }})<br>
**Room Type:** {{ ucfirst($room->type) }}<br>

## Dates & Times

**Check-in:** {{ $booking->check_in->format('l, F j, Y') }} at {{ $hotel->policies['check_in_time'] ?? '15:00' }}<br>
**Check-out:** {{ $booking->check_out->format('l, F j, Y') }} at {{ $hotel->policies['check_out_time'] ?? '11:00' }}<br>
**Duration:** {{ $booking->nights }} {{ Str::plural('night', $booking->nights) }}

## Pricing Breakdown

<x-mail::table>
| Item | Amount |
|:-----|-------:|
| Room Rate ({{ $booking->nights }} {{ Str::plural('night', $booking->nights) }}) | ${{ number_format($booking->subtotal, 2) }} |
| Taxes & Fees | ${{ number_format($booking->tax_amount, 2) }} |
| **Total Amount** | **${{ number_format($booking->total_amount, 2) }}** |
</x-mail::table>

## Important Information

- **Check-in Time:** {{ $hotel->policies['check_in_time'] ?? '3:00 PM' }}
- **Check-out Time:** {{ $hotel->policies['check_out_time'] ?? '11:00 AM' }}
- **Cancellation Policy:** {{ $hotel->policies['cancellation'] ?? 'Standard cancellation policy applies' }}

## What's Next?

<x-mail::button :url="route('customer.booking.show', $booking->id)" color="success">
View Booking Details
</x-mail::button>

If you have any questions or need to make changes to your reservation, please don't hesitate to contact us:

**{{ $hotel->name }}**<br>
📞 {{ $hotel->phone }}<br>
📧 {{ $hotel->email }}

We look forward to hosting you soon!

Thanks,<br>
The {{ $hotel->name }} Team<br>
{{ config('app.name') }}

---
<small>This is an automated confirmation email. Please save this email for your records.</small>
</x-mail::message>
