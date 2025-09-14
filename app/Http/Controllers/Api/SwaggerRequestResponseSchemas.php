<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     title="Login Request",
 *     description="User login request",
 *     required={"email", "password"},
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="User email address",
 *         example="admin@trivelo.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="User password",
 *         example="password"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     title="Register Request",
 *     description="User registration request",
 *     required={"name", "email", "password", "password_confirmation"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="User full name",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="User email address",
 *         example="john@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         minLength=8,
 *         description="User password",
 *         example="password123"
 *     ),
 *     @OA\Property(
 *         property="password_confirmation",
 *         type="string",
 *         format="password",
 *         description="Password confirmation",
 *         example="password123"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         nullable=true,
 *         description="User phone number",
 *         example="+1234567890"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="HotelCreateRequest",
 *     type="object",
 *     title="Hotel Create Request",
 *     description="Create hotel request",
 *     required={"name", "email", "phone", "address", "city", "country", "star_rating"},
 *     @OA\Property(property="name", type="string", description="Hotel name", example="Grand Plaza Hotel"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Hotel description", example="Luxury hotel in city center"),
 *     @OA\Property(property="email", type="string", format="email", description="Hotel contact email", example="info@grandplaza.com"),
 *     @OA\Property(property="phone", type="string", description="Hotel contact phone", example="+1234567890"),
 *     @OA\Property(property="website", type="string", nullable=true, description="Hotel website URL", example="https://grandplaza.com"),
 *     @OA\Property(property="address", type="string", description="Hotel address", example="123 Main Street"),
 *     @OA\Property(property="city", type="string", description="Hotel city", example="New York"),
 *     @OA\Property(property="state", type="string", nullable=true, description="Hotel state", example="NY"),
 *     @OA\Property(property="country", type="string", description="Hotel country", example="USA"),
 *     @OA\Property(property="postal_code", type="string", nullable=true, description="Hotel postal code", example="10001"),
 *     @OA\Property(property="latitude", type="number", format="float", nullable=true, description="Hotel latitude", example=40.7128),
 *     @OA\Property(property="longitude", type="number", format="float", nullable=true, description="Hotel longitude", example=-74.0060),
 *     @OA\Property(property="star_rating", type="integer", minimum=1, maximum=5, description="Hotel star rating", example=4),
 *     @OA\Property(property="check_in_time", type="string", format="time", description="Check-in time", example="15:00"),
 *     @OA\Property(property="check_out_time", type="string", format="time", description="Check-out time", example="11:00"),
 *     @OA\Property(property="cancellation_policy", type="string", nullable=true, description="Cancellation policy", example="Free cancellation up to 24 hours")
 * )
 *
 * @OA\Schema(
 *     schema="RoomCreateRequest",
 *     type="object",
 *     title="Room Create Request",
 *     description="Create room request",
 *     required={"room_number", "room_type", "price_per_night", "capacity"},
 *     @OA\Property(property="room_number", type="string", description="Room number", example="101"),
 *     @OA\Property(property="room_type", type="string", enum={"standard", "deluxe", "suite", "family", "executive"}, description="Room type", example="deluxe"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Room description", example="Spacious room with city view"),
 *     @OA\Property(property="price_per_night", type="number", format="float", minimum=0, description="Price per night", example=150.00),
 *     @OA\Property(property="capacity", type="integer", minimum=1, description="Room capacity", example=2),
 *     @OA\Property(property="bed_type", type="string", enum={"single", "double", "queen", "king", "twin"}, nullable=true, description="Bed type", example="king"),
 *     @OA\Property(property="bed_count", type="integer", minimum=1, nullable=true, description="Number of beds", example=1),
 *     @OA\Property(property="bathroom_count", type="integer", minimum=1, nullable=true, description="Number of bathrooms", example=1),
 *     @OA\Property(property="size_sqm", type="number", format="float", nullable=true, description="Room size in square meters", example=35.5),
 *     @OA\Property(property="floor_number", type="integer", nullable=true, description="Floor number", example=1),
 *     @OA\Property(property="has_balcony", type="boolean", description="Has balcony", example=true),
 *     @OA\Property(property="has_sea_view", type="boolean", description="Has sea view", example=false),
 *     @OA\Property(property="has_city_view", type="boolean", description="Has city view", example=true),
 *     @OA\Property(property="is_smoking_allowed", type="boolean", description="Smoking allowed", example=false),
 *     @OA\Property(property="is_accessible", type="boolean", description="Wheelchair accessible", example=true),
 *     @OA\Property(
 *         property="amenity_ids",
 *         type="array",
 *         description="Array of amenity IDs",
 *         @OA\Items(type="integer"),
 *         example={1, 2, 3}
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookingCreateRequest",
 *     type="object",
 *     title="Booking Create Request",
 *     description="Create booking request",
 *     required={"room_id", "check_in_date", "check_out_date", "guests_count"},
 *     @OA\Property(property="room_id", type="integer", description="Room ID", example=1),
 *     @OA\Property(property="check_in_date", type="string", format="date", description="Check-in date", example="2024-12-01"),
 *     @OA\Property(property="check_out_date", type="string", format="date", description="Check-out date", example="2024-12-05"),
 *     @OA\Property(property="guests_count", type="integer", minimum=1, description="Number of guests", example=2),
 *     @OA\Property(property="special_requests", type="string", nullable=true, description="Special requests", example="Late check-in requested")
 * )
 *
 * @OA\Schema(
 *     schema="AvailabilityCheckRequest",
 *     type="object",
 *     title="Availability Check Request",
 *     description="Room availability check request",
 *     required={"check_in_date", "check_out_date"},
 *     @OA\Property(property="check_in_date", type="string", format="date", description="Check-in date", example="2024-12-01"),
 *     @OA\Property(property="check_out_date", type="string", format="date", description="Check-out date", example="2024-12-05")
 * )
 *
 * @OA\Schema(
 *     schema="LoginResponse",
 *     type="object",
 *     title="Login Response",
 *     description="Successful login response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Login successful"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(
 *             property="user",
 *             ref="#/components/schemas/User"
 *         ),
 *         @OA\Property(
 *             property="token",
 *             type="string",
 *             description="Authentication token",
 *             example="1|abcdef123456789"
 *         ),
 *         @OA\Property(
 *             property="token_type",
 *             type="string",
 *             description="Token type",
 *             example="Bearer"
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="AvailabilityResponse",
 *     type="object",
 *     title="Availability Response",
 *     description="Room availability check response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Availability checked successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(property="room_id", type="integer", example=1),
 *         @OA\Property(property="check_in_date", type="string", format="date", example="2024-12-01"),
 *         @OA\Property(property="check_out_date", type="string", format="date", example="2024-12-05"),
 *         @OA\Property(property="is_available", type="boolean", example=true),
 *         @OA\Property(property="nights_count", type="integer", example=4),
 *         @OA\Property(property="total_amount", type="number", format="float", example=600.00),
 *         @OA\Property(property="message", type="string", example="Room is available for the selected dates")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="CreateHotelRequest",
 *     allOf={@OA\Schema(ref="#/components/schemas/HotelCreateRequest")}
 * )
 *
 * @OA\Schema(
 *     schema="CreateRoomRequest",
 *     allOf={@OA\Schema(ref="#/components/schemas/RoomCreateRequest")}
 * )
 *
 * @OA\Schema(
 *     schema="UpdateRoomRequest",
 *     type="object",
 *     title="Update Room Request",
 *     description="Update room request",
 *     @OA\Property(property="room_number", type="string", description="Room number", example="102"),
 *     @OA\Property(property="room_type", type="string", enum={"standard", "deluxe", "suite", "family", "executive"}, description="Room type", example="suite"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Room description", example="Updated room description"),
 *     @OA\Property(property="price_per_night", type="number", format="float", minimum=0, description="Price per night", example=200.00),
 *     @OA\Property(property="capacity", type="integer", minimum=1, description="Room capacity", example=4),
 *     @OA\Property(property="is_available", type="boolean", description="Room availability", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="CreateBookingRequest",
 *     allOf={@OA\Schema(ref="#/components/schemas/BookingCreateRequest")}
 * )
 *
 * @OA\Schema(
 *     schema="UpdateBookingRequest",
 *     type="object",
 *     title="Update Booking Request",
 *     description="Update booking request",
 *     @OA\Property(property="status", type="string", enum={"confirmed", "pending", "cancelled", "completed"}, description="Booking status", example="confirmed"),
 *     @OA\Property(property="special_requests", type="string", nullable=true, description="Special requests", example="Updated special request")
 * )
 *
 * @OA\Schema(
 *     schema="RoomListResponse",
 *     type="object",
 *     title="Room List Response",
 *     description="Paginated room list response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Rooms retrieved successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(
 *             property="data",
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/RoomSummary")
 *         ),
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=3),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="total", type="integer", example=42)
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="RoomDetailResponse",
 *     type="object",
 *     title="Room Detail Response",
 *     description="Single room detail response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Room details retrieved successfully"),
 *     @OA\Property(property="data", ref="#/components/schemas/RoomDetail")
 * )
 *
 * @OA\Schema(
 *     schema="RoomSummary",
 *     type="object",
 *     title="Room Summary",
 *     description="Room summary information",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="room_number", type="string", example="101"),
 *     @OA\Property(property="room_type", type="string", example="deluxe"),
 *     @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
 *     @OA\Property(property="capacity", type="integer", example=2),
 *     @OA\Property(property="is_available", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="RoomDetail",
 *     type="object",
 *     title="Room Detail",
 *     description="Detailed room information",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="hotel_id", type="integer", example=1),
 *     @OA\Property(property="room_number", type="string", example="101"),
 *     @OA\Property(property="room_type", type="string", example="deluxe"),
 *     @OA\Property(property="description", type="string", example="Spacious room with city view"),
 *     @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
 *     @OA\Property(property="capacity", type="integer", example=2),
 *     @OA\Property(property="bed_type", type="string", example="king"),
 *     @OA\Property(property="is_available", type="boolean", example=true),
 *     @OA\Property(
 *         property="hotel",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Grand Plaza Hotel"),
 *         @OA\Property(property="city", type="string", example="New York")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookingListResponse",
 *     type="object",
 *     title="Booking List Response",
 *     description="Paginated booking list response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Bookings retrieved successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(
 *             property="data",
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/BookingSummary")
 *         ),
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=2),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="total", type="integer", example=25)
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookingDetailResponse",
 *     type="object",
 *     title="Booking Detail Response",
 *     description="Single booking detail response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Booking retrieved successfully"),
 *     @OA\Property(property="data", ref="#/components/schemas/BookingDetail")
 * )
 *
 * @OA\Schema(
 *     schema="BookingSummary",
 *     type="object",
 *     title="Booking Summary",
 *     description="Booking summary information",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="booking_reference", type="string", example="TRV-2024-001"),
 *     @OA\Property(property="status", type="string", example="confirmed"),
 *     @OA\Property(property="check_in_date", type="string", format="date", example="2024-12-01"),
 *     @OA\Property(property="check_out_date", type="string", format="date", example="2024-12-05"),
 *     @OA\Property(property="total_amount", type="number", format="float", example=600.00),
 *     @OA\Property(
 *         property="hotel",
 *         type="object",
 *         @OA\Property(property="name", type="string", example="Grand Plaza Hotel")
 *     ),
 *     @OA\Property(
 *         property="room",
 *         type="object",
 *         @OA\Property(property="room_number", type="string", example="101"),
 *         @OA\Property(property="room_type", type="string", example="deluxe")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookingDetail",
 *     type="object",
 *     title="Booking Detail",
 *     description="Detailed booking information",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="booking_reference", type="string", example="TRV-2024-001"),
 *     @OA\Property(property="status", type="string", example="confirmed"),
 *     @OA\Property(property="check_in_date", type="string", format="date", example="2024-12-01"),
 *     @OA\Property(property="check_out_date", type="string", format="date", example="2024-12-05"),
 *     @OA\Property(property="total_amount", type="number", format="float", example=600.00),
 *     @OA\Property(property="guests_count", type="integer", example=2),
 *     @OA\Property(property="special_requests", type="string", example="Late check-in requested"),
 *     @OA\Property(property="nights", type="integer", example=4),
 *     @OA\Property(
 *         property="hotel",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Grand Plaza Hotel"),
 *         @OA\Property(property="city", type="string", example="New York"),
 *         @OA\Property(property="address", type="string", example="123 Main Street")
 *     ),
 *     @OA\Property(
 *         property="room",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="room_number", type="string", example="101"),
 *         @OA\Property(property="room_type", type="string", example="deluxe"),
 *         @OA\Property(property="price_per_night", type="number", format="float", example=150.00)
 *     )
 * )
 */
class SwaggerRequestResponseSchemas
{
    // This class is used solely for Swagger request/response schema definitions
}