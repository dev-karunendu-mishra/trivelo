<?php

namespace App\Http\Controllers\Api;

use App\Models\Hotel;
use App\Models\Amenity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelController extends BaseApiController
{
    /**
     * @OA\Get(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="List hotels",
     *     description="Get list of approved hotels with filtering and pagination",
     *
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="Filter by city",
     *         required=false,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="country",
     *         in="query",
     *         description="Filter by country",
     *         required=false,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="star_rating",
     *         in="query",
     *         description="Filter by star rating (1-5)",
     *         required=false,
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=5)
     *     ),
     *
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="Minimum room price",
     *         required=false,
     *
     *         @OA\Schema(type="number", format="float")
     *     ),
     *
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         description="Maximum room price",
     *         required=false,
     *
     *         @OA\Schema(type="number", format="float")
     *     ),
     *
     *     @OA\Parameter(
     *         name="amenities",
     *         in="query",
     *         description="Filter by amenity IDs",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="array",
     *
     *             @OA\Items(type="integer")
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="check_in",
     *         in="query",
     *         description="Check-in date (YYYY-MM-DD)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="check_out",
     *         in="query",
     *         description="Check-out date (YYYY-MM-DD)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="guests",
     *         in="query",
     *         description="Number of guests",
     *         required=false,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort by",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"price_asc", "price_desc", "rating_asc", "rating_desc", "name_asc", "name_desc"}
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Results per page (default: 15, max: 100)",
     *         required=false,
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Hotels retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="hotels",
     *                     type="array",
     *
     *                     @OA\Items(ref="#/components/schemas/Hotel")
     *                 )
     *             ),
     *
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="total_pages", type="integer", example=10),
     *                     @OA\Property(property="per_page", type="integer", example=15),
     *                     @OA\Property(property="total", type="integer", example=150)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Hotel::with(['amenities', 'user'])
            ->approved()
            ->active();

        // Apply filters
        if ($request->filled('city')) {
            $query->inCity($request->city);
        }

        if ($request->filled('country')) {
            $query->inCountry($request->country);
        }

        if ($request->filled('star_rating')) {
            $query->byStarRating($request->star_rating);
        }

        if ($request->filled('min_rating')) {
            $query->minStarRating($request->min_rating);
        }

        if ($request->filled('featured')) {
            $query->featured();
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSorts = ['name', 'star_rating', 'average_rating', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $hotels = $query->paginate($request->input('per_page', 15));

        return $this->successResponse([
            'hotels' => $hotels->items(),
            'pagination' => [
                'current_page' => $hotels->currentPage(),
                'per_page' => $hotels->perPage(),
                'total' => $hotels->total(),
                'last_page' => $hotels->lastPage(),
                'from' => $hotels->firstItem(),
                'to' => $hotels->lastItem(),
                'has_more_pages' => $hotels->hasMorePages(),
            ],
        ], 'Hotels retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Get hotel details",
     *     description="Get detailed information about a specific hotel",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Hotel ID",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Hotel details retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="hotel", ref="#/components/schemas/Hotel")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Hotel not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Hotel not found")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $hotel = Hotel::with(['amenities', 'user', 'rooms' => function ($query) {
            $query->active()->available();
        }])
        ->approved()
        ->active()
        ->find($id);

        if (!$hotel) {
            return $this->notFoundResponse('Hotel not found');
        }

        return $this->successResponse([
            'id' => $hotel->id,
            'name' => $hotel->name,
            'slug' => $hotel->slug,
            'description' => $hotel->description,
            'images' => $hotel->images,
            'email' => $hotel->email,
            'phone' => $hotel->phone,
            'address' => $hotel->address,
            'city' => $hotel->city,
            'state' => $hotel->state,
            'country' => $hotel->country,
            'postal_code' => $hotel->postal_code,
            'latitude' => $hotel->latitude,
            'longitude' => $hotel->longitude,
            'star_rating' => $hotel->star_rating,
            'average_rating' => $hotel->average_rating,
            'total_reviews' => $hotel->total_reviews,
            'policies' => $hotel->policies,
            'is_featured' => $hotel->is_featured,
            'verified_at' => $hotel->verified_at,
            'amenities' => $hotel->amenities,
            'manager' => [
                'id' => $hotel->user->id,
                'name' => $hotel->user->name,
                'email' => $hotel->user->email,
                'phone' => $hotel->user->phone,
            ],
            'room_types' => $hotel->getAvailableRoomTypes(),
            'price_range' => $hotel->getPriceRange(),
            'total_rooms' => $hotel->rooms->count(),
            'created_at' => $hotel->created_at,
            'updated_at' => $hotel->updated_at,
        ], 'Hotel details retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="Create hotel",
     *     description="Create a new hotel (requires hotel_manager role)",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/HotelCreateRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Hotel created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Hotel created successfully and submitted for approval"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="hotel", ref="#/components/schemas/Hotel")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Hotel manager role required",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Insufficient permissions")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Check if user is hotel manager
        if (!$user->isHotelManager() && !$user->isSuperAdmin()) {
            return $this->forbiddenResponse('Only hotel managers can create hotels');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'star_rating' => 'required|integer|between:1,5',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',
            'policies' => 'nullable|array',
        ]);

        $hotel = Hotel::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'star_rating' => $request->star_rating,
            'images' => $request->images,
            'policies' => $request->policies,
            'status' => 'pending', // Default status for new hotels
        ]);

        // Attach amenities if provided
        if ($request->filled('amenity_ids')) {
            $hotel->amenities()->attach($request->amenity_ids);
        }

        $hotel->load(['amenities', 'user']);

        return $this->successResponse([
            'id' => $hotel->id,
            'name' => $hotel->name,
            'slug' => $hotel->slug,
            'description' => $hotel->description,
            'status' => $hotel->status,
            'is_active' => $hotel->is_active,
            'created_at' => $hotel->created_at,
        ], 'Hotel created successfully', 201);
    }

    /**
     * Update hotel (only by owner or admin).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = Auth::user();
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return $this->notFoundResponse('Hotel not found');
        }

        // Check permissions
        if (!$user->isSuperAdmin() && $hotel->user_id !== $user->id) {
            return $this->forbiddenResponse('You can only update your own hotels');
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:500',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|max:100',
            'country' => 'sometimes|string|max:100',
            'postal_code' => 'sometimes|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'star_rating' => 'sometimes|integer|between:1,5',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',
            'policies' => 'nullable|array',
        ]);

        $hotel->update($request->only([
            'name', 'description', 'email', 'phone', 'address',
            'city', 'state', 'country', 'postal_code', 'latitude',
            'longitude', 'star_rating', 'images', 'policies'
        ]));

        // Update amenities if provided
        if ($request->has('amenity_ids')) {
            $hotel->amenities()->sync($request->amenity_ids);
        }

        $hotel->load(['amenities', 'user']);

        return $this->successResponse([
            'id' => $hotel->id,
            'name' => $hotel->name,
            'slug' => $hotel->slug,
            'description' => $hotel->description,
            'status' => $hotel->status,
            'updated_at' => $hotel->updated_at,
        ], 'Hotel updated successfully');
    }

    /**
     * Delete hotel (soft delete).
     */
    public function destroy(int $id): JsonResponse
    {
        $user = Auth::user();
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return $this->notFoundResponse('Hotel not found');
        }

        // Check permissions
        if (!$user->isSuperAdmin() && $hotel->user_id !== $user->id) {
            return $this->forbiddenResponse('You can only delete your own hotels');
        }

        $hotel->delete(); // Soft delete

        return $this->successResponse(null, 'Hotel deleted successfully');
    }

    /**
     * Get hotels managed by authenticated hotel manager.
     */
    public function myHotels(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->isHotelManager()) {
            return $this->forbiddenResponse('Only hotel managers can access this endpoint');
        }

        $query = Hotel::where('user_id', $user->id)
            ->with(['amenities', 'rooms']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hotels = $query->paginate($request->input('per_page', 15));

        return $this->successResponse([
            'hotels' => $hotels->items(),
            'pagination' => [
                'current_page' => $hotels->currentPage(),
                'per_page' => $hotels->perPage(),
                'total' => $hotels->total(),
                'last_page' => $hotels->lastPage(),
            ],
        ], 'Your hotels retrieved successfully');
    }
}
