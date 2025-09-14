<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends BaseApiController
{
    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="List user bookings",
     *     description="Get authenticated user's bookings with filtering and pagination",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by booking status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"confirmed", "pending", "cancelled", "completed"})
     *     ),
     *     @OA\Parameter(
     *         name="hotel_id",
     *         in="query",
     *         description="Filter by hotel ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Sort field",
     *         required=false,
     *         @OA\Schema(type="string", enum={"check_in_date", "check_out_date", "created_at"}, default="created_at")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
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
     *         description="Bookings retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BookingListResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Display a listing of user's bookings.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $query = Booking::with(['room.hotel', 'user', 'payments'])
            ->where('user_id', $user->id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSorts = ['check_in_date', 'check_out_date', 'total_amount', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $bookings = $query->paginate($request->input('per_page', 15));

        return $this->successResponse([
            'bookings' => $bookings->items(),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'last_page' => $bookings->lastPage(),
            ],
        ], 'Bookings retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="Create a new booking",
     *     description="Create a new room booking for authenticated user",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Booking creation data",
     *         @OA\JsonContent(ref="#/components/schemas/CreateBookingRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Booking created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BookingDetailResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or room not available",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Only customers can create bookings",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Store a new booking.
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->isCustomer() && !$user->isSuperAdmin()) {
            return $this->forbiddenResponse('Only customers can create bookings');
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
            'guest_details' => 'required|array',
            'guest_details.*.name' => 'required|string|max:255',
            'guest_details.*.age' => 'nullable|integer|min:1|max:120',
            'guest_details.*.id_type' => 'nullable|string|max:50',
            'guest_details.*.id_number' => 'nullable|string|max:100',
        ]);

        $room = Room::with('hotel')->find($request->room_id);

        // Check if room can accommodate guests
        if ($room->capacity < $request->number_of_guests) {
            return $this->errorResponse('Room cannot accommodate the requested number of guests');
        }

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);

        // Check availability
        $existingBooking = Booking::where('room_id', $request->room_id)
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

        if ($existingBooking) {
            return $this->errorResponse('Room is not available for the selected dates');
        }

        // Calculate total amount
        $baseAmount = $room->base_price * $nights;
        $taxAmount = $baseAmount * 0.18; // 18% tax
        $totalAmount = $baseAmount + $taxAmount;

        DB::beginTransaction();

        try {
            $booking = Booking::create([
                'user_id' => $user->id,
                'hotel_id' => $room->hotel_id,
                'room_id' => $request->room_id,
                'booking_reference' => $this->generateBookingReference(),
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'number_of_guests' => $request->number_of_guests,
                'number_of_nights' => $nights,
                'room_rate' => $room->base_price,
                'subtotal' => $baseAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'special_requests' => $request->special_requests,
                'guest_details' => $request->guest_details,
                'status' => 'pending',
            ]);

            DB::commit();

            $booking->load(['room.hotel', 'user']);

            return $this->successResponse([
                'booking' => [
                    'id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'status' => $booking->status,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'number_of_guests' => $booking->number_of_guests,
                    'number_of_nights' => $booking->number_of_nights,
                    'total_amount' => $booking->total_amount,
                    'hotel' => [
                        'id' => $booking->room->hotel->id,
                        'name' => $booking->room->hotel->name,
                        'address' => $booking->room->hotel->address,
                        'city' => $booking->room->hotel->city,
                        'phone' => $booking->room->hotel->phone,
                    ],
                    'room' => [
                        'id' => $booking->room->id,
                        'name' => $booking->room->name,
                        'room_number' => $booking->room->room_number,
                        'type' => $booking->room->type,
                    ],
                    'created_at' => $booking->created_at,
                ],
            ], 'Booking created successfully', 201);

        } catch (\Exception $e) {
            DB::rollback();
            return $this->serverErrorResponse('Failed to create booking');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/bookings/{id}",
     *     tags={"Bookings"},
     *     summary="Get booking details",
     *     description="Get detailed information about a specific booking",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Booking retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BookingDetailResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Can only view own bookings",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * Display the specified booking.
     */
    public function show(int $id): JsonResponse
    {
        $user = Auth::user();
        
        $booking = Booking::with(['room.hotel', 'user', 'payments'])
            ->where('id', $id)
            ->first();

        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }

        // Check ownership or admin access
        if (!$user->isSuperAdmin() && $booking->user_id !== $user->id && 
            (!$user->isHotelManager() || $booking->room->hotel->user_id !== $user->id)) {
            return $this->forbiddenResponse('You can only view your own bookings');
        }

        return $this->successResponse([
            'id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'status' => $booking->status,
            'check_in_date' => $booking->check_in_date,
            'check_out_date' => $booking->check_out_date,
            'number_of_guests' => $booking->number_of_guests,
            'number_of_nights' => $booking->number_of_nights,
            'room_rate' => $booking->room_rate,
            'subtotal' => $booking->subtotal,
            'tax_amount' => $booking->tax_amount,
            'total_amount' => $booking->total_amount,
            'special_requests' => $booking->special_requests,
            'guest_details' => $booking->guest_details,
            'hotel' => [
                'id' => $booking->room->hotel->id,
                'name' => $booking->room->hotel->name,
                'address' => $booking->room->hotel->address,
                'city' => $booking->room->hotel->city,
                'phone' => $booking->room->hotel->phone,
                'email' => $booking->room->hotel->email,
            ],
            'room' => [
                'id' => $booking->room->id,
                'name' => $booking->room->name,
                'room_number' => $booking->room->room_number,
                'type' => $booking->room->type,
                'capacity' => $booking->room->capacity,
            ],
            'customer' => [
                'id' => $booking->user->id,
                'name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone,
            ],
            'payments' => $booking->payments,
            'created_at' => $booking->created_at,
            'updated_at' => $booking->updated_at,
        ], 'Booking details retrieved successfully');
    }

    /**
     * Update booking status.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = Auth::user();
        $booking = Booking::with(['room.hotel'])->find($id);

        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }

        // Check permissions
        $canUpdate = $user->isSuperAdmin() || 
                    ($user->isCustomer() && $booking->user_id === $user->id) ||
                    ($user->isHotelManager() && $booking->room->hotel->user_id === $user->id);

        if (!$canUpdate) {
            return $this->forbiddenResponse('You cannot modify this booking');
        }

        $request->validate([
            'status' => 'sometimes|in:pending,confirmed,checked_in,checked_out,cancelled',
            'special_requests' => 'sometimes|nullable|string|max:1000',
            'guest_details' => 'sometimes|array',
        ]);

        // Status change logic
        if ($request->has('status')) {
            $newStatus = $request->status;
            
            // Only customers can cancel their own bookings (before check-in)
            if ($newStatus === 'cancelled' && $user->isCustomer()) {
                if ($booking->status === 'checked_in') {
                    return $this->errorResponse('Cannot cancel after check-in');
                }
            }
            
            // Only hotel managers can confirm/check-in/check-out
            if (in_array($newStatus, ['confirmed', 'checked_in', 'checked_out']) && !$user->isHotelManager() && !$user->isSuperAdmin()) {
                return $this->forbiddenResponse('Only hotel managers can change booking to this status');
            }
        }

        $booking->update($request->only(['status', 'special_requests', 'guest_details']));

        return $this->successResponse([
            'id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'status' => $booking->status,
            'updated_at' => $booking->updated_at,
        ], 'Booking updated successfully');
    }

    /**
     * Cancel a booking.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = Auth::user();
        $booking = Booking::with(['room.hotel'])->find($id);

        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }

        // Check permissions - only customer can cancel their own booking or admin
        if (!$user->isSuperAdmin() && $booking->user_id !== $user->id) {
            return $this->forbiddenResponse('You can only cancel your own bookings');
        }

        // Check if cancellation is allowed
        if ($booking->status === 'checked_in') {
            return $this->errorResponse('Cannot cancel after check-in');
        }

        if ($booking->status === 'cancelled') {
            return $this->errorResponse('Booking is already cancelled');
        }

        $booking->update(['status' => 'cancelled']);

        return $this->successResponse([
            'id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'status' => $booking->status,
        ], 'Booking cancelled successfully');
    }

    /**
     * Get hotel bookings (for hotel managers).
     */
    public function hotelBookings(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->isHotelManager() && !$user->isSuperAdmin()) {
            return $this->forbiddenResponse('Only hotel managers can access hotel bookings');
        }

        $query = Booking::with(['room', 'user']);

        if ($user->isHotelManager()) {
            // Filter by hotels owned by the manager
            $hotelIds = Hotel::where('user_id', $user->id)->pluck('id');
            $query->whereIn('hotel_id', $hotelIds);
        }

        // Apply filters
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('check_in_date')) {
            $query->whereDate('check_in_date', $request->check_in_date);
        }

        $bookings = $query->paginate($request->input('per_page', 15));

        return $this->successResponse([
            'bookings' => $bookings->items(),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'last_page' => $bookings->lastPage(),
            ],
        ], 'Hotel bookings retrieved successfully');
    }

    /**
     * Generate unique booking reference.
     */
    private function generateBookingReference(): string
    {
        do {
            $reference = 'TRV' . date('Y') . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
        } while (Booking::where('booking_reference', $reference)->exists());

        return $reference;
    }
}
