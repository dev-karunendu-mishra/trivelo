<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *     required={"id", "name", "email", "email_verified_at", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", format="int64", description="User ID", example=1),
 *     @OA\Property(property="name", type="string", maxLength=255, description="User full name", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, description="User email address", example="john@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, description="Email verification timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="phone", type="string", maxLength=20, nullable=true, description="User phone number", example="+1234567890"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", nullable=true, description="User date of birth", example="1990-01-01"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, nullable=true, description="User gender", example="male"),
 *     @OA\Property(property="address", type="string", nullable=true, description="User address", example="123 Main St"),
 *     @OA\Property(property="city", type="string", maxLength=100, nullable=true, description="User city", example="New York"),
 *     @OA\Property(property="state", type="string", maxLength=100, nullable=true, description="User state", example="NY"),
 *     @OA\Property(property="country", type="string", maxLength=100, nullable=true, description="User country", example="USA"),
 *     @OA\Property(property="postal_code", type="string", maxLength=20, nullable=true, description="User postal code", example="10001"),
 *     @OA\Property(property="is_active", type="boolean", description="User active status", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(
 *         property="roles",
 *         type="array",
 *         description="User roles",
 *         @OA\Items(ref="#/components/schemas/Role")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     title="Role",
 *     description="User role",
 *     required={"id", "name", "guard_name"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Role ID", example=1),
 *     @OA\Property(property="name", type="string", description="Role name", example="customer"),
 *     @OA\Property(property="guard_name", type="string", description="Guard name", example="web")
 * )
 *
 * @OA\Schema(
 *     schema="Hotel",
 *     type="object",
 *     title="Hotel",
 *     description="Hotel model",
 *     required={"id", "name", "email", "phone", "address", "city", "country", "star_rating", "status", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Hotel ID", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="Hotel owner/manager user ID", example=2),
 *     @OA\Property(property="name", type="string", maxLength=255, description="Hotel name", example="Grand Plaza Hotel"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Hotel description", example="Luxury hotel in city center"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, description="Hotel contact email", example="info@grandplaza.com"),
 *     @OA\Property(property="phone", type="string", maxLength=20, description="Hotel contact phone", example="+1234567890"),
 *     @OA\Property(property="website", type="string", nullable=true, description="Hotel website URL", example="https://grandplaza.com"),
 *     @OA\Property(property="address", type="string", description="Hotel address", example="123 Main Street"),
 *     @OA\Property(property="city", type="string", maxLength=100, description="Hotel city", example="New York"),
 *     @OA\Property(property="state", type="string", maxLength=100, nullable=true, description="Hotel state", example="NY"),
 *     @OA\Property(property="country", type="string", maxLength=100, description="Hotel country", example="USA"),
 *     @OA\Property(property="postal_code", type="string", maxLength=20, nullable=true, description="Hotel postal code", example="10001"),
 *     @OA\Property(property="latitude", type="number", format="float", nullable=true, description="Hotel latitude", example=40.7128),
 *     @OA\Property(property="longitude", type="number", format="float", nullable=true, description="Hotel longitude", example=-74.0060),
 *     @OA\Property(property="star_rating", type="integer", minimum=1, maximum=5, description="Hotel star rating", example=4),
 *     @OA\Property(property="check_in_time", type="string", format="time", description="Check-in time", example="15:00:00"),
 *     @OA\Property(property="check_out_time", type="string", format="time", description="Check-out time", example="11:00:00"),
 *     @OA\Property(property="cancellation_policy", type="string", nullable=true, description="Cancellation policy", example="Free cancellation up to 24 hours"),
 *     @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected", "suspended"}, description="Hotel status", example="approved"),
 *     @OA\Property(property="is_active", type="boolean", description="Hotel active status", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(
 *         property="rooms",
 *         type="array",
 *         description="Hotel rooms",
 *         @OA\Items(ref="#/components/schemas/Room")
 *     ),
 *     @OA\Property(
 *         property="amenities",
 *         type="array",
 *         description="Hotel amenities",
 *         @OA\Items(ref="#/components/schemas/Amenity")
 *     ),
 *     @OA\Property(property="rooms_count", type="integer", description="Total number of rooms", example=50),
 *     @OA\Property(property="available_rooms_count", type="integer", description="Number of available rooms", example=15),
 *     @OA\Property(property="average_rating", type="number", format="float", description="Average rating", example=4.5),
 *     @OA\Property(property="reviews_count", type="integer", description="Total number of reviews", example=125)
 * )
 *
 * @OA\Schema(
 *     schema="Room",
 *     type="object",
 *     title="Room",
 *     description="Hotel room model",
 *     required={"id", "hotel_id", "room_number", "room_type", "price_per_night", "capacity", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Room ID", example=1),
 *     @OA\Property(property="hotel_id", type="integer", format="int64", description="Hotel ID", example=1),
 *     @OA\Property(property="room_number", type="string", maxLength=20, description="Room number", example="101"),
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
 *     @OA\Property(property="is_available", type="boolean", description="Room availability status", example=true),
 *     @OA\Property(property="is_active", type="boolean", description="Room active status", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(
 *         property="hotel",
 *         description="Associated hotel",
 *         ref="#/components/schemas/Hotel"
 *     ),
 *     @OA\Property(
 *         property="amenities",
 *         type="array",
 *         description="Room amenities",
 *         @OA\Items(ref="#/components/schemas/Amenity")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Booking",
 *     type="object",
 *     title="Booking",
 *     description="Hotel booking model",
 *     required={"id", "user_id", "hotel_id", "room_id", "check_in_date", "check_out_date", "guests_count", "total_amount", "status", "payment_status", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Booking ID", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="Customer user ID", example=1),
 *     @OA\Property(property="hotel_id", type="integer", format="int64", description="Hotel ID", example=1),
 *     @OA\Property(property="room_id", type="integer", format="int64", description="Room ID", example=1),
 *     @OA\Property(property="booking_reference", type="string", maxLength=20, description="Booking reference number", example="BK-2024-001"),
 *     @OA\Property(property="check_in_date", type="string", format="date", description="Check-in date", example="2024-12-01"),
 *     @OA\Property(property="check_out_date", type="string", format="date", description="Check-out date", example="2024-12-05"),
 *     @OA\Property(property="guests_count", type="integer", minimum=1, description="Number of guests", example=2),
 *     @OA\Property(property="nights_count", type="integer", minimum=1, description="Number of nights", example=4),
 *     @OA\Property(property="room_rate", type="number", format="float", minimum=0, description="Room rate per night", example=150.00),
 *     @OA\Property(property="subtotal", type="number", format="float", minimum=0, description="Subtotal amount", example=600.00),
 *     @OA\Property(property="tax_amount", type="number", format="float", minimum=0, description="Tax amount", example=60.00),
 *     @OA\Property(property="total_amount", type="number", format="float", minimum=0, description="Total booking amount", example=660.00),
 *     @OA\Property(property="currency", type="string", maxLength=3, description="Currency code", example="USD"),
 *     @OA\Property(property="status", type="string", enum={"pending", "confirmed", "checked_in", "checked_out", "cancelled", "completed"}, description="Booking status", example="confirmed"),
 *     @OA\Property(property="payment_status", type="string", enum={"pending", "partial", "paid", "failed", "refunded"}, description="Payment status", example="paid"),
 *     @OA\Property(property="special_requests", type="string", nullable=true, description="Special requests", example="Late check-in requested"),
 *     @OA\Property(property="cancelled_at", type="string", format="date-time", nullable=true, description="Cancellation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="cancellation_reason", type="string", nullable=true, description="Cancellation reason", example="Change of plans"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(
 *         property="user",
 *         description="Customer details",
 *         ref="#/components/schemas/User"
 *     ),
 *     @OA\Property(
 *         property="hotel",
 *         description="Hotel details",
 *         ref="#/components/schemas/Hotel"
 *     ),
 *     @OA\Property(
 *         property="room",
 *         description="Room details",
 *         ref="#/components/schemas/Room"
 *     ),
 *     @OA\Property(
 *         property="payments",
 *         type="array",
 *         description="Associated payments",
 *         @OA\Items(ref="#/components/schemas/Payment")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Amenity",
 *     type="object",
 *     title="Amenity",
 *     description="Hotel/Room amenity model",
 *     required={"id", "name", "type", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Amenity ID", example=1),
 *     @OA\Property(property="name", type="string", maxLength=100, description="Amenity name", example="Free WiFi"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Amenity description", example="High-speed internet access"),
 *     @OA\Property(property="type", type="string", enum={"hotel", "room", "both"}, description="Amenity type", example="both"),
 *     @OA\Property(property="icon", type="string", maxLength=50, nullable=true, description="Amenity icon", example="wifi"),
 *     @OA\Property(property="is_active", type="boolean", description="Amenity active status", example=true),
 *     @OA\Property(property="sort_order", type="integer", nullable=true, description="Sort order", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2024-01-01T12:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Payment",
 *     type="object",
 *     title="Payment",
 *     description="Payment model",
 *     required={"id", "booking_id", "user_id", "amount", "currency", "payment_method", "status", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Payment ID", example=1),
 *     @OA\Property(property="booking_id", type="integer", format="int64", description="Booking ID", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="User ID", example=1),
 *     @OA\Property(property="payment_reference", type="string", maxLength=100, nullable=true, description="Payment reference", example="PAY-2024-001"),
 *     @OA\Property(property="amount", type="number", format="float", minimum=0, description="Payment amount", example=660.00),
 *     @OA\Property(property="currency", type="string", maxLength=3, description="Currency code", example="USD"),
 *     @OA\Property(property="payment_method", type="string", enum={"credit_card", "debit_card", "paypal", "bank_transfer", "cash"}, description="Payment method", example="credit_card"),
 *     @OA\Property(property="payment_gateway", type="string", maxLength=50, nullable=true, description="Payment gateway", example="stripe"),
 *     @OA\Property(property="gateway_transaction_id", type="string", maxLength=255, nullable=true, description="Gateway transaction ID", example="txn_1234567890"),
 *     @OA\Property(property="status", type="string", enum={"pending", "completed", "failed", "cancelled", "refunded"}, description="Payment status", example="completed"),
 *     @OA\Property(property="paid_at", type="string", format="date-time", nullable=true, description="Payment completion timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="failed_at", type="string", format="date-time", nullable=true, description="Payment failure timestamp", example=null),
 *     @OA\Property(property="failure_reason", type="string", nullable=true, description="Payment failure reason", example=null),
 *     @OA\Property(property="refunded_at", type="string", format="date-time", nullable=true, description="Refund timestamp", example=null),
 *     @OA\Property(property="refund_amount", type="number", format="float", nullable=true, description="Refund amount", example=null),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(
 *         property="booking",
 *         description="Associated booking",
 *         ref="#/components/schemas/Booking"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         description="User details",
 *         ref="#/components/schemas/User"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Review",
 *     type="object",
 *     title="Review",
 *     description="Hotel review model",
 *     required={"id", "user_id", "hotel_id", "booking_id", "rating", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Review ID", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="User ID", example=1),
 *     @OA\Property(property="hotel_id", type="integer", format="int64", description="Hotel ID", example=1),
 *     @OA\Property(property="booking_id", type="integer", format="int64", description="Booking ID", example=1),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, description="Rating (1-5 stars)", example=4),
 *     @OA\Property(property="title", type="string", maxLength=255, nullable=true, description="Review title", example="Great stay!"),
 *     @OA\Property(property="comment", type="string", nullable=true, description="Review comment", example="The hotel was excellent with great service."),
 *     @OA\Property(property="pros", type="string", nullable=true, description="Review pros", example="Clean rooms, friendly staff"),
 *     @OA\Property(property="cons", type="string", nullable=true, description="Review cons", example="WiFi was slow"),
 *     @OA\Property(property="is_approved", type="boolean", description="Review approval status", example=true),
 *     @OA\Property(property="is_featured", type="boolean", description="Featured review status", example=false),
 *     @OA\Property(property="approved_at", type="string", format="date-time", nullable=true, description="Approval timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(
 *         property="user",
 *         description="User details",
 *         ref="#/components/schemas/User"
 *     ),
 *     @OA\Property(
 *         property="hotel",
 *         description="Hotel details",
 *         ref="#/components/schemas/Hotel"
 *     ),
 *     @OA\Property(
 *         property="booking",
 *         description="Associated booking",
 *         ref="#/components/schemas/Booking"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     title="API Response",
 *     description="Standard API response format",
 *     @OA\Property(property="success", type="boolean", description="Operation success status", example=true),
 *     @OA\Property(property="message", type="string", description="Response message", example="Operation completed successfully"),
 *     @OA\Property(property="data", type="object", description="Response data", nullable=true),
 *     @OA\Property(property="errors", type="array", description="Error details", nullable=true, @OA\Items(type="string"))
 * )
 *
 * @OA\Schema(
 *     schema="PaginatedResponse",
 *     type="object",
 *     title="Paginated Response",
 *     description="Paginated API response format",
 *     @OA\Property(property="success", type="boolean", description="Operation success status", example=true),
 *     @OA\Property(property="message", type="string", description="Response message", example="Data retrieved successfully"),
 *     @OA\Property(property="data", type="array", description="Response data items", @OA\Items(type="object")),
 *     @OA\Property(
 *         property="pagination",
 *         type="object",
 *         description="Pagination information",
 *         @OA\Property(property="current_page", type="integer", description="Current page number", example=1),
 *         @OA\Property(property="per_page", type="integer", description="Items per page", example=15),
 *         @OA\Property(property="total", type="integer", description="Total number of items", example=250),
 *         @OA\Property(property="last_page", type="integer", description="Last page number", example=17)
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     title="Error Response",
 *     description="Error API response format",
 *     @OA\Property(property="success", type="boolean", description="Operation success status", example=false),
 *     @OA\Property(property="message", type="string", description="Error message", example="Validation failed"),
 *     @OA\Property(property="data", type="object", nullable=true),
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         description="Detailed error information",
 *         @OA\Items(type="string"),
 *         example={"The email field is required.", "The password must be at least 8 characters."}
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     type="object",
 *     title="Success Response",
 *     description="Success API response format",
 *     @OA\Property(property="success", type="boolean", description="Operation success status", example=true),
 *     @OA\Property(property="message", type="string", description="Success message", example="Operation completed successfully"),
 *     @OA\Property(property="data", type="object", nullable=true, description="Response data")
 * )
 */
class SwaggerSchemas
{
    // This class is used solely for Swagger schema definitions
}