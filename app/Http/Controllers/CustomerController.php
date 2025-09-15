<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Hotel;
use App\Models\UserFavorite;
use App\Models\Notification;
use App\Services\EmailService;
use Carbon\Carbon;

class CustomerController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get booking statistics
        $bookingStats = [
            'total_bookings' => $user->bookings()->count(),
            'upcoming_bookings' => $user->bookings()->upcoming()->count(),
            'completed_bookings' => $user->bookings()->past()->count(),
            'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
        ];
        
        // Get recent bookings
        $recentBookings = $user->bookings()
            ->with(['hotel', 'room'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get upcoming bookings
        $upcomingBookings = $user->bookings()
            ->upcoming()
            ->with(['hotel', 'room'])
            ->orderBy('check_in_date', 'asc')
            ->limit(3)
            ->get();
        
        // Get favorite hotels (based on bookings)
        $favoriteHotels = Hotel::whereIn('id',
            $user->bookings()
                ->select('hotel_id')
                ->groupBy('hotel_id')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('hotel_id')
        )->limit(3)->get();
        
        return view('customer.dashboard', compact(
            'user',
            'bookingStats',
            'recentBookings',
            'upcomingBookings',
            'favoriteHotels'
        ));
    }

    /**
     * Display customer bookings with filtering and search
     */
    public function bookings(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->bookings()->with(['hotel', 'room', 'payment']);
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'current') {
                $query->current();
            } elseif ($request->status === 'past') {
                $query->past();
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Search by hotel name or booking number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('hotel', function($hotelQuery) use ($search) {
                      $hotelQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sort options
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'check_in':
                    $query->orderBy('check_in_date', 'desc');
                    break;
                case 'amount':
                    $query->orderBy('total_amount', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $bookings = $query->paginate(10);
        
        // Get booking statistics for filters
        $stats = [
            'total' => $user->bookings()->count(),
            'upcoming' => $user->bookings()->upcoming()->count(),
            'completed' => $user->bookings()->past()->count(),
            'cancelled' => $user->bookings()->where('status', 'cancelled')->count(),
        ];
        
        return view('customer.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Show specific booking details
     */
    public function showBooking(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $booking->load(['hotel', 'room', 'payment', 'review']);
        
        return view('customer.bookings.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        if (!$booking->canBeCancelled()) {
            return redirect()->back()->with('error', 'This booking cannot be cancelled.');
        }
        
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);
        
        $refundAmount = $booking->calculateRefundAmount();
        $booking->refund_amount = $refundAmount;
        $booking->cancel($request->cancellation_reason);

        // Send cancellation email
        $booking->load(['user', 'hotel', 'room']);
        $this->emailService->sendBookingCancellation($booking, $refundAmount);
        
        return redirect()->route('customer.bookings')
            ->with('success', 'Booking cancelled successfully.' .
                ($refundAmount > 0 ? " Refund of $" . number_format($refundAmount, 2) . " will be processed within 3-5 business days." : ''));
    }

    /**
     * Display customer profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile.edit', compact('user'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'date_of_birth', 'address']));

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update customer preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'preferred_currency' => 'nullable|in:USD,EUR,GBP,CAD',
            'preferred_language' => 'nullable|in:en,es,fr,de',
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
            'marketing_emails' => 'nullable|boolean',
        ]);

        $user->update([
            'preferred_currency' => $request->preferred_currency,
            'preferred_language' => $request->preferred_language,
            'email_notifications' => $request->boolean('email_notifications'),
            'sms_notifications' => $request->boolean('sms_notifications'),
            'marketing_emails' => $request->boolean('marketing_emails'),
        ]);

        return redirect()->route('customer.profile')->with('success', 'Preferences updated successfully!');
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('customer.profile')->with('success', 'Password changed successfully!');
    }

    /**
     * Update profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::exists('public/profile_pictures/' . basename($user->profile_picture))) {
                Storage::delete('public/profile_pictures/' . basename($user->profile_picture));
            }

            $fileName = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs('public/profile_pictures', $fileName);
            
            $user->update([
                'profile_picture' => Storage::url('profile_pictures/' . $fileName)
            ]);
        }

        return redirect()->route('customer.profile')->with('success', 'Profile picture updated successfully!');
    }

    /**
     * Show customer reviews
     */
    public function reviews()
    {
        $user = Auth::user();
        $reviews = $user->reviews()->with('hotel', 'booking')->latest()->paginate(10);
        
        $reviewStats = [
            'total_reviews' => $reviews->total(),
            'average_rating' => $user->reviews()->avg('rating'),
            'recent_reviews' => $user->reviews()->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('customer.reviews.index', compact('reviews', 'reviewStats'));
    }

    /**
     * Show create review form
     */
    public function createReview(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if booking is completed and no review exists
        if (!in_array($booking->status, ['checked_out', 'completed']) || $booking->review) {
            return redirect()->back()->with('error', 'Review cannot be created for this booking.');
        }
        
        return view('customer.reviews.create', compact('booking'));
    }

    /**
     * Store review
     */
    public function storeReview(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|min:10|max:1000',
        ]);
        
        Review::create([
            'user_id' => Auth::id(),
            'hotel_id' => $booking->hotel_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => 'pending', // Will be approved by admin
        ]);
        
        // Update hotel average rating
        $booking->hotel->updateAverageRating();
        
        return redirect()->route('customer.bookings')
            ->with('success', 'Review submitted successfully. It will be published after approval.');
    }

    /**
     * Show notifications/inbox
     */
    public function notifications(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->notifications()->latest();
        
        // Filter by type if specified
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        
        // Filter by status if specified  
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }
        
        // Only show active (non-expired) notifications by default
        if (!$request->filled('show_expired')) {
            $query->active();
        }
        
        $notifications = $query->paginate(20);
        
        // Get notification stats
        $stats = [
            'total' => $user->notifications()->count(),
            'unread' => $user->notifications()->unread()->count(),
            'read' => $user->notifications()->read()->count(),
            'today' => $user->notifications()->whereDate('created_at', today())->count(),
        ];
        
        // Available notification types for filter
        $types = [
            'booking_confirmation' => 'Booking Confirmations',
            'booking_reminder' => 'Booking Reminders', 
            'payment_confirmation' => 'Payment Confirmations',
            'promotional' => 'Promotions',
            'system' => 'System',
            'review_reminder' => 'Review Reminders',
        ];
        
        return view('customer.notifications.index', compact('notifications', 'stats', 'types'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        // Redirect to action URL if it exists
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        Auth::user()->notifications()->unread()->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete notification
     */
    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Get unread notifications count for AJAX
     */
    public function getUnreadNotificationsCount()
    {
        $count = Auth::user()->notifications()->unread()->count();
        
        return response()->json(['count' => $count]);
    }

    // Wishlist/Favorites

    /**
     * Display user's wishlist/favorites
     */
    public function wishlist(Request $request)
    {
        $user = Auth::user();
        
        $favorites = $user->favoriteHotels()
            ->with(['images', 'location', 'rooms'])
            ->paginate(12);

        return view('customer.wishlist.index', [
            'favorites' => $favorites,
            'theme' => $this->getTheme()
        ]);
    }

    /**
     * Add hotel to favorites
     */
    public function addToWishlist(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id'
        ]);

        $user = Auth::user();
        $hotelId = $request->hotel_id;

        // Check if already in wishlist
        $exists = $user->favorites()->where('hotel_id', $hotelId)->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel is already in your wishlist'
            ], 400);
        }

        $user->favorites()->create([
            'hotel_id' => $hotelId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hotel added to wishlist successfully'
        ]);
    }

    /**
     * Remove hotel from favorites
     */
    public function removeFromWishlist(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id'
        ]);

        $user = Auth::user();
        $hotelId = $request->hotel_id;

        $favorite = $user->favorites()->where('hotel_id', $hotelId)->first();
        
        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel is not in your wishlist'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hotel removed from wishlist successfully'
        ]);
    }

    // Helper methods

    private function getTheme()
    {
        return session('theme', 'classic');
    }
}
