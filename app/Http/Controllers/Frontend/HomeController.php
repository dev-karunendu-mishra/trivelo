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
     * Handle hotel search with advanced filtering
     */
    public function search(Request $request)
    {
        $request->validate([
            'destination' => 'nullable|string|max:255',
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'guests' => 'nullable|integer|min:1|max:10',
            'rooms' => 'nullable|integer|min:1|max:5',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gt:min_price',
            'star_rating' => 'nullable|array',
            'star_rating.*' => 'integer|min:1|max:5',
            'guest_rating' => 'nullable|array',
            'guest_rating.*' => 'numeric|min:0|max:5',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'sort' => 'nullable|string|in:relevance,price_low,price_high,rating,star_rating,name',
        ]);

        $query = Hotel::where('is_active', true)
                     ->where('status', 'approved')
                     ->with(['images', 'location', 'amenities', 'rooms', 'reviews']);

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

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->whereHas('rooms', function ($roomQuery) use ($request) {
                $roomQuery->where('base_price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('rooms', function ($roomQuery) use ($request) {
                $roomQuery->where('base_price', '<=', $request->max_price);
            });
        }

        // Filter by star rating
        if ($request->filled('star_rating')) {
            $starRatings = $request->star_rating;
            $query->whereIn('star_rating', $starRatings);
        }

        // Filter by guest rating
        if ($request->filled('guest_rating')) {
            $guestRatings = $request->guest_rating;
            $query->where(function ($q) use ($guestRatings) {
                foreach ($guestRatings as $rating) {
                    $q->orWhere('average_rating', '>=', $rating);
                }
            });
        }

        // Filter by amenities
        if ($request->filled('amenities')) {
            $amenities = $request->amenities;
            foreach ($amenities as $amenity) {
                $query->where(function ($q) use ($amenity) {
                    $q->whereJsonContains('amenities', $amenity)
                      ->orWhereHas('amenities', function ($amenityQuery) use ($amenity) {
                          $amenityQuery->where('name', 'like', "%{$amenity}%")
                                      ->orWhere('slug', $amenity);
                      });
                });
            }
        }

        // Apply sorting
        $sort = $request->get('sort', 'relevance');
        switch ($sort) {
            case 'price_low':
                $query->join('rooms', 'hotels.id', '=', 'rooms.hotel_id')
                      ->select('hotels.*', DB::raw('MIN(rooms.base_price) as min_price'))
                      ->groupBy('hotels.id')
                      ->orderBy('min_price', 'asc');
                break;
            case 'price_high':
                $query->join('rooms', 'hotels.id', '=', 'rooms.hotel_id')
                      ->select('hotels.*', DB::raw('MIN(rooms.base_price) as min_price'))
                      ->groupBy('hotels.id')
                      ->orderBy('min_price', 'desc');
                break;
            case 'rating':
                $query->orderByDesc('average_rating');
                break;
            case 'star_rating':
                $query->orderByDesc('star_rating');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'relevance':
            default:
                $query->orderByDesc('is_featured')
                      ->orderByDesc('average_rating')
                      ->orderByDesc('star_rating');
                break;
        }

        // If no sorting was applied via join, ensure we still order by something for consistency
        if (!in_array($sort, ['price_low', 'price_high'])) {
            $query->orderByDesc('created_at');
        }

        $hotels = $query->paginate(12)->appends($request->query());

        // Add min_price to each hotel for map markers
        $hotels->getCollection()->transform(function ($hotel) {
            $priceRange = $hotel->getPriceRange();
            $hotel->min_price = $priceRange['min'];
            return $hotel;
        });

        return view('frontend.hotels.search', compact('hotels'))->with([
            'searchParams' => $request->only([
                'destination', 'check_in', 'check_out', 'guests', 'rooms',
                'min_price', 'max_price', 'star_rating', 'guest_rating', 'amenities', 'sort'
            ]),
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

    /**
     * Handle AJAX hotel search for real-time filtering
     */
    public function searchAjax(Request $request)
    {
        $request->validate([
            'destination' => 'nullable|string|max:255',
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'guests' => 'nullable|integer|min:1|max:10',
            'rooms' => 'nullable|integer|min:1|max:5',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gt:min_price',
            'star_rating' => 'nullable|array',
            'star_rating.*' => 'integer|min:1|max:5',
            'guest_rating' => 'nullable|array',
            'guest_rating.*' => 'numeric|min:0|max:5',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'sort' => 'nullable|string|in:relevance,price_low,price_high,rating,star_rating,name',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Hotel::where('is_active', true)
                     ->where('status', 'approved')
                     ->with(['images', 'location', 'amenities', 'rooms', 'reviews']);

        // Apply the same filtering logic as the main search method
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

        if ($request->filled('min_price')) {
            $query->whereHas('rooms', function ($roomQuery) use ($request) {
                $roomQuery->where('base_price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('rooms', function ($roomQuery) use ($request) {
                $roomQuery->where('base_price', '<=', $request->max_price);
            });
        }

        if ($request->filled('star_rating')) {
            $starRatings = $request->star_rating;
            $query->whereIn('star_rating', $starRatings);
        }

        if ($request->filled('guest_rating')) {
            $guestRatings = $request->guest_rating;
            $query->where(function ($q) use ($guestRatings) {
                foreach ($guestRatings as $rating) {
                    $q->orWhere('average_rating', '>=', $rating);
                }
            });
        }

        if ($request->filled('amenities')) {
            $amenities = $request->amenities;
            foreach ($amenities as $amenity) {
                $query->where(function ($q) use ($amenity) {
                    $q->whereJsonContains('amenities', $amenity)
                      ->orWhereHas('amenities', function ($amenityQuery) use ($amenity) {
                          $amenityQuery->where('name', 'like', "%{$amenity}%")
                                      ->orWhere('slug', $amenity);
                      });
                });
            }
        }

        // Apply sorting
        $sort = $request->get('sort', 'relevance');
        switch ($sort) {
            case 'price_low':
                $query->join('rooms', 'hotels.id', '=', 'rooms.hotel_id')
                      ->select('hotels.*', DB::raw('MIN(rooms.base_price) as min_price'))
                      ->groupBy('hotels.id')
                      ->orderBy('min_price', 'asc');
                break;
            case 'price_high':
                $query->join('rooms', 'hotels.id', '=', 'rooms.hotel_id')
                      ->select('hotels.*', DB::raw('MIN(rooms.base_price) as min_price'))
                      ->groupBy('hotels.id')
                      ->orderBy('min_price', 'desc');
                break;
            case 'rating':
                $query->orderByDesc('average_rating');
                break;
            case 'star_rating':
                $query->orderByDesc('star_rating');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'relevance':
            default:
                $query->orderByDesc('is_featured')
                      ->orderByDesc('average_rating')
                      ->orderByDesc('star_rating');
                break;
        }

        if (!in_array($sort, ['price_low', 'price_high'])) {
            $query->orderByDesc('created_at');
        }

        $hotels = $query->paginate(12)->appends($request->query());

        // Add min_price to each hotel
        $hotels->getCollection()->transform(function ($hotel) {
            $priceRange = $hotel->getPriceRange();
            $hotel->min_price = $priceRange['min'];
            return $hotel;
        });

        // Return JSON response with hotels data and pagination info
        return response()->json([
            'hotels' => $hotels->items(),
            'pagination' => [
                'current_page' => $hotels->currentPage(),
                'last_page' => $hotels->lastPage(),
                'per_page' => $hotels->perPage(),
                'total' => $hotels->total(),
                'from' => $hotels->firstItem(),
                'to' => $hotels->lastItem(),
                'has_more_pages' => $hotels->hasMorePages(),
                'links' => $hotels->links('pagination::bootstrap-4')->toHtml()
            ]
        ]);
    }
}
