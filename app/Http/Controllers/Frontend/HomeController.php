<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        // Get featured hotels (cached for performance)
        $featuredHotels = Cache::remember('featured_hotels', 3600, function () {
            return Hotel::where('is_featured', true)
                       ->where('is_active', true)
                       ->with(['images', 'location', 'amenities'])
                       ->limit(6)
                       ->get();
        });

        // Get popular destinations
        $popularDestinations = Cache::remember('popular_destinations', 3600, function () {
            return Destination::where('is_popular', true)
                             ->with('hotels')
                             ->limit(8)
                             ->get();
        });

        // Get quick stats for homepage
        $stats = Cache::remember('homepage_stats', 1800, function () {
            return [
                'total_hotels' => Hotel::where('is_active', true)->count(),
                'total_destinations' => Destination::count(),
                'happy_customers' => 15420,
                'years_experience' => now()->year - 2020,
            ];
        });

        return view('frontend.homepage', compact('featuredHotels', 'popularDestinations', 'stats'));
    }

    /**
     * Handle hotel search with basic filtering
     */
    public function search(Request $request)
    {
        $request->validate([
            'destination' => 'nullable|string|max:255',
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'guests' => 'nullable|integer|min:1|max:10',
            'rooms' => 'nullable|integer|min:1|max:5',
        ]);

        $query = Hotel::where('is_active', true)
                     ->where('status', 'approved')
                     ->with(['images', 'location', 'amenities', 'rooms']);

        // Filter by destination
        if ($request->filled('destination')) {
            $destination = $request->destination;
            $query->where(function ($q) use ($destination) {
                $q->where('name', 'like', "%{$destination}%")
                  ->orWhere('city', 'like', "%{$destination}%")
                  ->orWhere('address', 'like', "%{$destination}%")
                  ->orWhereHas('location', function ($locQuery) use ($destination) {
                      $locQuery->where('city', 'like', "%{$destination}%")
                               ->orWhere('state', 'like', "%{$destination}%")
                               ->orWhere('country', 'like', "%{$destination}%");
                  });
            });
        }

        // Basic sorting (relevance by default)
        $query->orderBy('is_featured', 'desc')
              ->orderBy('average_rating', 'desc')
              ->orderBy('star_rating', 'desc');

        $hotels = $query->paginate(12)->appends($request->query());

        return view('frontend.hotels.search', compact('hotels'))->with([
            'searchParams' => $request->only(['destination', 'check_in', 'check_out', 'guests', 'rooms']),
        ]);
    }

    /**
     * Show hotel details
     */
    public function hotelDetails($id)
    {
        $hotel = Hotel::where('is_active', true)
                     ->where('status', 'approved')
                     ->with(['images', 'location', 'amenities', 'rooms.images'])
                     ->findOrFail($id);

        // Get similar hotels
        $similarHotels = Hotel::where('is_active', true)
                             ->where('status', 'approved')
                             ->where('id', '!=', $hotel->id)
                             ->whereHas('location', function ($query) use ($hotel) {
                                 if ($hotel->location) {
                                     $query->where('city', $hotel->location->city);
                                 } else {
                                     $query->where('city', $hotel->city);
                                 }
                             })
                             ->with(['images', 'location'])
                             ->limit(4)
                             ->get();

        return view('frontend.hotels.details', compact('hotel', 'similarHotels'));
    }

    /**
     * Get destinations for autocomplete
     */
    public function getDestinations(Request $request)
    {
        $search = $request->get('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $destinations = Cache::remember("destinations_search_{$search}", 300, function () use ($search) {
            $results = collect();
            
            // Search in destinations table
            $destinationResults = Destination::where('is_active', true)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('city', 'like', "%{$search}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($destination) {
                    return [
                        'value' => $destination->name,
                        'label' => $destination->name . ', ' . $destination->country,
                    ];
                });
            
            // Search in hotel cities
            $cityResults = Hotel::select('city', 'country')
                ->where('is_active', true)
                ->where('status', 'approved')
                ->where('city', 'like', "%{$search}%")
                ->distinct()
                ->limit(5)
                ->get()
                ->map(function ($location) {
                    return [
                        'value' => $location->city,
                        'label' => $location->city . ', ' . $location->country,
                    ];
                });

            return $results->concat($destinationResults)->concat($cityResults)->unique('value')->take(10);
        });

        return response()->json($destinations);
    }
}
