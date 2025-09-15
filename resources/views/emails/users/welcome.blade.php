<x-mail::message>
# Welcome to {{ config('app.name') }}! 🎉

Hello {{ $user->name }},

Welcome to {{ config('app.name') }}! We're thrilled to have you join our community of travelers who trust us to make their hotel booking experience seamless and memorable.

## What You Can Do Now

<x-mail::table>
| Feature | Description |
|:--------|:------------|
| 🏨 Browse Hotels | Discover amazing hotels worldwide with detailed photos and reviews |
| 🔍 Smart Search | Use our advanced filters to find the perfect accommodation |
| 💝 Save Favorites | Create your wishlist of dream destinations |
| 📱 Easy Booking | Book in just a few clicks with secure payment |
| ⭐ Leave Reviews | Share your experiences to help fellow travelers |
| 📊 Track Bookings | Manage all your reservations in your dashboard |
</x-mail::table>

## Get Started

<x-mail::button :url="route('home')">
Start Exploring Hotels
</x-mail::button>

## Quick Tips for First-Time Users

<x-mail::panel>
**💡 Pro Tips:**

✅ **Complete your profile** for faster bookings
✅ **Enable notifications** to stay updated on your bookings
✅ **Check our deals section** for exclusive offers
✅ **Read reviews** from other travelers before booking
✅ **Contact hotels directly** if you have special requests
</x-mail::panel>

## Popular Destinations

Here are some of our most popular destinations to get you started:

- **New York City** - The city that never sleeps
- **Paris** - City of lights and romance
- **Tokyo** - Where tradition meets innovation
- **London** - Rich history and modern culture
- **Dubai** - Luxury and adventure combined

## Need Help?

Our support team is here to help you 24/7. Whether you have questions about booking, need travel advice, or encounter any issues, we're just an email away.

<x-mail::button :url="'mailto:' . config('mail.support_email', 'support@trivelo.com')">
Contact Support
</x-mail::button>

## Stay Connected

Follow us on social media for travel tips, exclusive deals, and destination inspiration:

- 📘 Facebook: @TreveloTravel
- 📸 Instagram: @trivelo_official
- 🐦 Twitter: @TreveloBooking
- 📧 Newsletter: Get weekly deals delivered to your inbox

Thanks for choosing {{ config('app.name') }}. We can't wait to help you create amazing travel memories!

Happy travels! ✈️

{{ config('app.name') }} Team

---

**P.S.** Keep an eye on your inbox for exclusive welcome offers coming your way! 🎁

*This email was sent because you created an account on {{ config('app.name') }}. If you believe this was sent in error, please contact our support team.*
</x-mail::message>
