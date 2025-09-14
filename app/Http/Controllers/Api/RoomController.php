<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends BaseApiController
{
    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="List rooms for a hotel",
     *     description="Get list of active rooms for a specific hotel with filtering",
     *
     *     @OA\Parameter(
     *         name="hotel_id",
     *         in="query",
     *         description="Hotel ID (required)",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="room_type",
     *         in="query",
     *         description="Filter by room type",
     *         required=false,
     *         @OA\Schema(type="string", enum={"standard", "deluxe", "suite", "presidential"})
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="Minimum room price per night",
     *         required=false,
     *         @OA\Schema(type="number", format="float", minimum=0)
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         description="Maximum room price per night",
     *         required=false,
     *         @OA\Schema(type="number", format="float", minimum=0)
     *     ),
     *     @OA\Parameter(
     *         name="capacity",
     *         in="query",
     *         description="Minimum room capacity",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Rooms retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RoomListResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error - hotel_id required",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hotel not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Display a listing of rooms for a specific hotel.
     */
    public function index(Request $request): JsonResponse
    {
        $hotelId = $request->input('hotel_id');
        
        if (!$hotelId) {
            return $this->errorResponse('hotel_id parameter is required', 400);
        }

        $hotel = Hotel::find($hotelId);
        if (!$hotel) {
            return $this->notFoundResponse('Hotel not found');
        }

        $query = Room::with(['amenities'])
            ->where('hotel_id', $hotelId)
            ->active()
            ->available();

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'base_price');
        $sortOrder = $request->input('sort_order', 'asc');
        
        $allowedSorts = ['base_price', 'capacity', 'size_sqft', 'type'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $rooms = $query->paginate($request->input('per_page', 15));

        return $this->successResponse([
            'hotel' => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'city' => $hotel->city,
                'star_rating' => $hotel->star_rating,
            ],
            'rooms' => $rooms->items(),
            'pagination' => [
                'current_page' => $rooms->currentPage(),
                'per_page' => $rooms->perPage(),
                'total' => $rooms->total(),
                'last_page' => $rooms->lastPage(),
            ],
        ], 'Rooms retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Get room details",
     *     description="Get detailed information about a specific room",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Room retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RoomDetailResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Display the specified room.
     */
    public function show(int $id): JsonResponse
    {
        $room = Room::with(['hotel', 'amenities'])->find($id);

        if (!$room) {
            return $this->notFoundResponse('Room not found');
        }

        return $this->successResponse([
            'id' => $room->id,
            'hotel_id' => $room->hotel_id,
            'hotel' => [
                'id' => $room->hotel->id,
                'name' => $room->hotel->name,
                'city' => $room->hotel->city,
                'star_rating' => $room->hotel->star_rating,
            ],
            'name' => $room->name,
            'room_number' => $room->room_number,
            'type' => $room->type,
            'description' => $room->description,
            'images' => $room->images,
            'capacity' => $room->capacity,
            'beds' => $room->beds,
            'bed_type' => $room->bed_type,
            'base_price' => $room->base_price,
            'weekend_price' => $room->weekend_price,
            'holiday_price' => $room->holiday_price,
            'size_sqft' => $room->size_sqft,
            'features' => $room->features,
            'is_smoking' => $room->is_smoking,
            'is_accessible' => $room->is_accessible,
            'floor_number' => $room->floor_number,
            'amenities' => $room->amenities,
            'is_available' => $room->is_available,
            'is_active' => $room->is_active,
        ], 'Room details retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="Create a new room",
     *     description="Create a new room for a hotel (Hotel Manager only)",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Room creation data",
     *         @OA\JsonContent(ref="#/components/schemas/CreateRoomRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Room created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RoomDetailResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Only hotel managers can create rooms",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hotel not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Store a newly created room (Hotel Manager only).
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->isHotelManager() && !$user->isSuperAdmin()) {
            return $this->forbiddenResponse('Only hotel managers can create rooms');
        }

        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:50',
            'type' => 'required|in:standard,deluxe,suite,presidential,family,twin,single,double',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'capacity' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'bed_type' => 'required|in:single,double,queen,king,twin,sofa_bed',
            'base_price' => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'holiday_price' => 'nullable|numeric|min:0',
            'size_sqft' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'is_smoking' => 'boolean',
            'is_accessible' => 'boolean',
            'floor_number' => 'nullable|integer|min:0',
            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',
        ]);

        // Verify hotel ownership
        $hotel = Hotel::find($request->hotel_id);
        if (!$user->isSuperAdmin() && $hotel->user_id !== $user->id) {
            return $this->forbiddenResponse('You can only create rooms for your own hotels');
        }

        $room = Room::create($request->only([
            'hotel_id', 'name', 'room_number', 'type', 'description',
            'images', 'capacity', 'beds', 'bed_type', 'base_price',
            'weekend_price', 'holiday_price', 'size_sqft', 'features',
            'is_smoking', 'is_accessible', 'floor_number'
        ]));

        // Attach amenities if provided
        if ($request->filled('amenity_ids')) {
            $room->amenities()->attach($request->amenity_ids);
        }

        $room->load(['amenities', 'hotel']);

        return $this->successResponse([
            'id' => $room->id,
            'name' => $room->name,
            'room_number' => $room->room_number,
            'type' => $room->type,
            'base_price' => $room->base_price,
            'is_available' => $room->is_available,
            'is_active' => $room->is_active,
            'created_at' => $room->created_at,
        ], 'Room created successfully', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Update room",
     *     description="Update room details (Hotel Manager only)",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Room update data",
     *         @OA\JsonContent(ref="#/components/schemas/UpdateRoomRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Room updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RoomDetailResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Only room owner can update",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Update the specified room.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = Auth::user();
        $room = Room::find($id);

        if (!$room) {
            return $this->notFoundResponse('Room not found');
        }

        // Verify hotel ownership
        if (!$user->isSuperAdmin() && $room->hotel->user_id !== $user->id) {
            return $this->forbiddenResponse('You can only update rooms from your own hotels');
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'room_number' => 'nullable|string|max:50',
            'type' => 'sometimes|in:standard,deluxe,suite,presidential,family,twin,single,double',
            'description' => 'sometimes|string',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'capacity' => 'sometimes|integer|min:1',
            'beds' => 'sometimes|integer|min:1',
            'bed_type' => 'sometimes|in:single,double,queen,king,twin,sofa_bed',
            'base_price' => 'sometimes|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'holiday_price' => 'nullable|numeric|min:0',
            'size_sqft' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'is_smoking' => 'boolean',
            'is_accessible' => 'boolean',
            'is_available' => 'boolean',
            'is_active' => 'boolean',
            'floor_number' => 'nullable|integer|min:0',
            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',
        ]);

        $room->update($request->only([
            'name', 'room_number', 'type', 'description', 'images',
            'capacity', 'beds', 'bed_type', 'base_price', 'weekend_price',
            'holiday_price', 'size_sqft', 'features', 'is_smoking',
            'is_accessible', 'is_available', 'is_active', 'floor_number'
        ]));

        // Update amenities if provided
        if ($request->has('amenity_ids')) {
            $room->amenities()->sync($request->amenity_ids);
        }

        $room->load(['amenities', 'hotel']);

        return $this->successResponse([
            'id' => $room->id,
            'name' => $room->name,
            'type' => $room->type,
            'base_price' => $room->base_price,
            'is_available' => $room->is_available,
            'is_active' => $room->is_active,
            'updated_at' => $room->updated_at,
        ], 'Room updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Delete room",
     *     description="Delete a room (Hotel Manager only)",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Room deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Only room owner can delete",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Remove the specified room.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = Auth::user();
        $room = Room::find($id);

        if (!$room) {
            return $this->notFoundResponse('Room not found');
        }

        // Verify hotel ownership
        if (!$user->isSuperAdmin() && $room->hotel->user_id !== $user->id) {
            return $this->forbiddenResponse('You can only delete rooms from your own hotels');
        }

        $room->delete(); // Soft delete

        return $this->successResponse(null, 'Room deleted successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/rooms/{id}/check-availability",
     *     tags={"Rooms"},
     *     summary="Check room availability",
     *     description="Check if a room is available for the specified dates",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Date range for availability check",
     *         @OA\JsonContent(
     *             required={"check_in", "check_out"},
     *             @OA\Property(property="check_in", type="string", format="date", example="2024-12-01"),
     *             @OA\Property(property="check_out", type="string", format="date", example="2024-12-05")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Availability check result",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Room availability checked successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="available", type="boolean", example=true),
     *                 @OA\Property(property="room_id", type="integer", example=1),
     *                 @OA\Property(property="check_in", type="string", format="date", example="2024-12-01"),
     *                 @OA\Property(property="check_out", type="string", format="date", example="2024-12-05"),
     *                 @OA\Property(property="total_nights", type="integer", example=4),
     *                 @OA\Property(property="total_price", type="number", format="float", example=600.00)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Check room availability for given dates.
     */
    public function checkAvailability(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'sometimes|integer|min:1',
        ]);

        $room = Room::find($id);

        if (!$room) {
            return $this->notFoundResponse('Room not found');
        }

        // Check if room can accommodate guests
        $guests = $request->input('guests', 1);
        if ($room->capacity < $guests) {
            return $this->errorResponse('Room cannot accommodate the requested number of guests');
        }

        // Check for existing bookings in the requested date range
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;

        $existingBooking = $room->bookings()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                          ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->first();

        $isAvailable = !$existingBooking && $room->is_available && $room->is_active;

        return $this->successResponse([
            'room_id' => $room->id,
            'is_available' => $isAvailable,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => $guests,
            'base_price' => $room->base_price,
            'total_nights' => \Carbon\Carbon::parse($checkIn)->diffInDays($checkOut),
            'estimated_total' => $room->base_price * \Carbon\Carbon::parse($checkIn)->diffInDays($checkOut),
            'message' => $isAvailable ? 'Room is available for the selected dates' : 'Room is not available for the selected dates',
        ], 'Availability checked successfully');
    }
}
