<x-mail::message>
# Booking Reminder

Hello {{ $user->name }},

@if($daysUntilCheckIn === 1)
**Tomorrow is your check-in day!** We hope you're excited for your upcoming stay.
@else
Your check-in is **{{ $daysUntilCheckIn }} days away**. We wanted to remind you about your upcoming reservation.
@endif

## Booking Details

**Booking Reference:** #{{ $booking->booking_number }}
**Hotel:** {{ $hotel->name }}
**Room:** {{ $room->type }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
**Guests:** {{ $booking->guests }} guest{{ $booking->guests > 1 ? 's' : '' }}

@if($booking->special_requests)
**Special Requests:** {{ $booking->special_requests }}
@endif

<x-mail::panel>
**Hotel Address:**
{{ $hotel->address }}
{{ $hotel->city }}, {{ $hotel->state }} {{ $hotel->zip_code }}

**Hotel Contact:**
📞 {{ $hotel->phone ?? 'Contact information available at hotel' }}
</x-mail::panel>

## What to Bring

<x-mail::table>
| Essential Items | Notes |
|:-------------- |:------|
| Valid ID/Passport | Required for check-in |
| Credit/Debit Card | For incidentals and security deposit |
| Booking Confirmation | This email or booking reference |
| Travel Documents | If applicable |
</x-mail::table>

## Check-in Information

- **Check-in Time:** 3:00 PM onwards
- **Check-out Time:** 11:00 AM
- **Early check-in/Late check-out:** Contact hotel directly

<x-mail::button :url="route('customer.bookings.show', $booking->id)">
View Booking Details
</x-mail::button>

@if($daysUntilCheckIn <= 3)
We recommend confirming your transportation to the hotel and reviewing any local COVID-19 guidelines that may be in place.
@endif

If you need to make any changes to your booking or have questions, please contact us immediately.

Thanks,<br>
{{ config('app.name') }} Team

---

**Need Help?**
If you have any questions, reply to this email or contact our support team.
</x-mail::message>
