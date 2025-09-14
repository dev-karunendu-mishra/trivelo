<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="Hotel",
 *     type="object",
 *     title="Hotel",
 *     description="Hotel basic information",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Grand Hotel NYC"),
 *     @OA\Property(property="slug", type="string", example="grand-hotel-nyc"),
 *     @OA\Property(property="description", type="string", example="Luxury hotel in the heart of Manhattan"),
 *     @OA\Property(property="address", type="string", example="123 Broadway, New York, NY 10001"),
 *     @OA\Property(property="city", type="string", example="New York"),
 *     @OA\Property(property="state", type="string", example="NY"),
 *     @OA\Property(property="country", type="string", example="USA"),
 *     @OA\Property(property="star_rating", type="integer", example=4),
 *     @OA\Property(property="min_price", type="number", format="float", example=150.00),
 *     @OA\Property(
 *         property="images",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/HotelImage")
 *     ),
 *
 *     @OA\Property(
 *         property="amenities",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/Amenity")
 *     ),
 *
 *     @OA\Property(property="average_rating", type="number", format="float", example=4.2),
 *     @OA\Property(property="total_reviews", type="integer", example=156)
 * )
 *
 * @OA\Schema(
 *     schema="HotelDetail",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Hotel"),
 *         @OA\Schema(
 *             type="object",
 *
 *             @OA\Property(property="postal_code", type="string", example="10001"),
 *             @OA\Property(property="phone", type="string", example="+1234567890"),
 *             @OA\Property(property="email", type="string", example="info@grandhotel.com"),
 *             @OA\Property(property="website", type="string", example="https://grandhotel.com"),
 *             @OA\Property(property="check_in_time", type="string", format="time", example="14:00:00"),
 *             @OA\Property(property="check_out_time", type="string", format="time", example="11:00:00"),
 *             @OA\Property(property="policies", type="string", example="Cancellation policy: 24 hours..."),
 *             @OA\Property(property="latitude", type="number", format="float", example=40.7589),
 *             @OA\Property(property="longitude", type="number", format="float", example=-73.9851),
 *             @OA\Property(
 *                 property="rooms",
 *                 type="array",
 *
 *                 @OA\Items(ref="#/components/schemas/Room")
 *             ),
 *
 *             @OA\Property(
 *                 property="reviews",
 *                 type="object",
 *                 @OA\Property(property="average_rating", type="number", format="float", example=4.2),
 *                 @OA\Property(property="total_reviews", type="integer", example=156),
 *                 @OA\Property(
 *                     property="rating_breakdown",
 *                     type="object",
 *                     @OA\Property(property="5", type="integer", example=45),
 *                     @OA\Property(property="4", type="integer", example=78),
 *                     @OA\Property(property="3", type="integer", example=25),
 *                     @OA\Property(property="2", type="integer", example=6),
 *                     @OA\Property(property="1", type="integer", example=2)
 *                 )
 *             )
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="Room",
 *     type="object",
 *     title="Room",
 *     description="Room information",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="hotel_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Deluxe Room"),
 *     @OA\Property(property="type", type="string", enum={"single", "double", "twin", "suite", "deluxe", "family"}, example="deluxe"),
 *     @OA\Property(property="description", type="string", example="Spacious room with city view"),
 *     @OA\Property(property="capacity", type="integer", example=2),
 *     @OA\Property(property="base_price", type="number", format="float", example=150.00),
 *     @OA\Property(property="area", type="number", format="float", example=25.5),
 *     @OA\Property(property="bed_type", type="string", example="King Bed"),
 *     @OA\Property(property="quantity", type="integer", example=10),
 *     @OA\Property(property="available_quantity", type="integer", example=3),
 *     @OA\Property(
 *         property="images",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/RoomImage")
 *     ),
 *
 *     @OA\Property(
 *         property="amenities",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/Amenity")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Booking",
 *     type="object",
 *     title="Booking",
 *     description="Booking information",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="booking_number", type="string", example="BK-2025-001"),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="hotel_id", type="integer", example=1),
 *     @OA\Property(property="room_id", type="integer", example=1),
 *     @OA\Property(property="check_in_date", type="string", format="date", example="2025-10-15"),
 *     @OA\Property(property="check_out_date", type="string", format="date", example="2025-10-18"),
 *     @OA\Property(property="adults", type="integer", example=2),
 *     @OA\Property(property="children", type="integer", example=1),
 *     @OA\Property(property="nights", type="integer", example=3),
 *     @OA\Property(property="room_price", type="number", format="float", example=150.00),
 *     @OA\Property(property="total_amount", type="number", format="float", example=450.00),
 *     @OA\Property(property="tax_amount", type="number", format="float", example=45.00),
 *     @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled", "completed", "no_show"}, example="confirmed"),
 *     @OA\Property(property="guest_name", type="string", example="John Doe"),
 *     @OA\Property(property="guest_email", type="string", example="john@example.com"),
 *     @OA\Property(property="guest_phone", type="string", example="+1234567890"),
 *     @OA\Property(property="special_requests", type="string", example="Late check-in requested"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-14T10:30:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Payment",
 *     type="object",
 *     title="Payment",
 *     description="Payment information",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="booking_id", type="integer", example=1),
 *     @OA\Property(property="payment_method", type="string", enum={"stripe", "paypal", "bank_transfer", "cash"}, example="stripe"),
 *     @OA\Property(property="transaction_id", type="string", example="pi_1234567890"),
 *     @OA\Property(property="amount", type="number", format="float", example=450.00),
 *     @OA\Property(property="currency", type="string", example="USD"),
 *     @OA\Property(property="status", type="string", enum={"pending", "completed", "failed", "refunded", "partially_refunded"}, example="completed"),
 *     @OA\Property(property="payment_date", type="string", format="date-time", example="2025-09-14T10:30:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Review",
 *     type="object",
 *     title="Review",
 *     description="Review information",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="booking_id", type="integer", example=1),
 *     @OA\Property(property="hotel_id", type="integer", example=1),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="title", type="string", example="Excellent stay!"),
 *     @OA\Property(property="comment", type="string", example="The hotel exceeded our expectations..."),
 *     @OA\Property(property="cleanliness_rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="service_rating", type="integer", minimum=1, maximum=5, example=4),
 *     @OA\Property(property="location_rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="value_rating", type="integer", minimum=1, maximum=5, example=4),
 *     @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected"}, example="approved"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-14T10:30:00Z"),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="avatar", type="string", example="/storage/avatars/user-1.jpg")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Amenity",
 *     type="object",
 *     title="Amenity",
 *     description="Amenity information",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="WiFi"),
 *     @OA\Property(property="slug", type="string", example="wifi"),
 *     @OA\Property(property="icon", type="string", example="fas fa-wifi"),
 *     @OA\Property(property="type", type="string", enum={"hotel", "room", "both"}, example="both")
 * )
 *
 * @OA\Schema(
 *     schema="HotelImage",
 *     type="object",
 *     title="Hotel Image",
 *     description="Hotel image information",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="image_path", type="string", example="/storage/hotels/hotel-1-main.jpg"),
 *     @OA\Property(property="alt_text", type="string", example="Hotel exterior"),
 *     @OA\Property(property="is_primary", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="RoomImage",
 *     type="object",
 *     title="Room Image",
 *     description="Room image information",
 *
 *     @OA\Property(property="id", type="integer", example=3),
 *     @OA\Property(property="image_path", type="string", example="/storage/rooms/room-1-main.jpg"),
 *     @OA\Property(property="alt_text", type="string", example="Deluxe room"),
 *     @OA\Property(property="is_primary", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="CreateHotelRequest",
 *     type="object",
 *     title="Create Hotel Request",
 *     description="Request body for creating a new hotel",
 *     required={"name", "address", "city", "state", "country", "phone", "email", "star_rating"},
 *
 *     @OA\Property(property="name", type="string", example="My Hotel"),
 *     @OA\Property(property="description", type="string", example="A wonderful place to stay"),
 *     @OA\Property(property="address", type="string", example="123 Main St, City, State"),
 *     @OA\Property(property="city", type="string", example="City Name"),
 *     @OA\Property(property="state", type="string", example="State Name"),
 *     @OA\Property(property="country", type="string", example="Country Name"),
 *     @OA\Property(property="postal_code", type="string", example="12345"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="email", type="string", format="email", example="hotel@example.com"),
 *     @OA\Property(property="website", type="string", example="https://myhotel.com"),
 *     @OA\Property(property="star_rating", type="integer", minimum=1, maximum=5, example=4),
 *     @OA\Property(property="check_in_time", type="string", format="time", example="14:00"),
 *     @OA\Property(property="check_out_time", type="string", format="time", example="11:00"),
 *     @OA\Property(property="policies", type="string", example="Hotel policies text..."),
 *     @OA\Property(
 *         property="amenities",
 *         type="array",
 *
 *         @OA\Items(type="integer"),
 *         example={1, 2, 3}
 *     )
 * )
 */
class SwaggerSchemas
{
    // This class is just for organizing Swagger schema definitions
}
