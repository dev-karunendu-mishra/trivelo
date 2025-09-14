# API Documentation - Trivelo Hotel Booking System

## Base URL
- **Development**: `http://localhost:8000/api`
- **Production**: `https://your-domain.com/api`

## Authentication
All authenticated endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_jwt_token}
```

## Response Format
All API responses follow this standard format:
```json
{
    "success": true|false,
    "message": "Response message",
    "data": {}, // Response data (if any)
    "errors": {}, // Validation errors (if any)
    "meta": {
        "pagination": {}, // Pagination info (if applicable)
        "timestamp": "2025-09-14T10:30:00Z"
    }
}
```

## HTTP Status Codes
- `200` - OK (Success)
- `201` - Created
- `204` - No Content
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Unprocessable Entity (Validation Error)
- `429` - Too Many Requests
- `500` - Internal Server Error

---

## 🔐 Authentication Endpoints

### Register
**POST** `/auth/register`

Register a new user account.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890",
    "role": "customer" // optional: customer, hotel_manager
}
```

**Response:**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "status": "active",
            "created_at": "2025-09-14T10:30:00Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
    }
}
```

### Login
**POST** `/auth/login`

Authenticate user and get access token.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "roles": ["customer"]
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "expires_in": 3600
    }
}
```

### Logout
**POST** `/auth/logout`

Logout user and invalidate token.

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### Refresh Token
**POST** `/auth/refresh`

Refresh the authentication token.

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
    "success": true,
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "expires_in": 3600
    }
}
```

---

## 🏨 Hotel Endpoints

### List Hotels (Public)
**GET** `/hotels`

Get list of approved hotels with filtering and pagination.

**Query Parameters:**
- `city` (string) - Filter by city
- `country` (string) - Filter by country
- `star_rating` (integer) - Filter by star rating (1-5)
- `min_price` (decimal) - Minimum room price
- `max_price` (decimal) - Maximum room price
- `amenities[]` (array) - Filter by amenity IDs
- `check_in` (date) - Check-in date (YYYY-MM-DD)
- `check_out` (date) - Check-out date (YYYY-MM-DD)
- `guests` (integer) - Number of guests
- `sort` (string) - Sort by: price_asc, price_desc, rating_asc, rating_desc, name_asc, name_desc
- `per_page` (integer) - Results per page (default: 15, max: 100)
- `page` (integer) - Page number

**Example Request:**
```
GET /hotels?city=New York&star_rating=4&min_price=100&max_price=300&amenities[]=1&amenities[]=2&sort=price_asc&per_page=20&page=1
```

**Response:**
```json
{
    "success": true,
    "data": {
        "hotels": [
            {
                "id": 1,
                "name": "Grand Hotel NYC",
                "slug": "grand-hotel-nyc",
                "description": "Luxury hotel in the heart of Manhattan",
                "address": "123 Broadway, New York, NY 10001",
                "city": "New York",
                "state": "NY",
                "country": "USA",
                "star_rating": 4,
                "min_price": 150.00,
                "images": [
                    {
                        "id": 1,
                        "image_path": "/storage/hotels/hotel-1-main.jpg",
                        "alt_text": "Hotel exterior",
                        "is_primary": true
                    }
                ],
                "amenities": [
                    {"id": 1, "name": "WiFi", "icon": "fas fa-wifi"},
                    {"id": 2, "name": "Swimming Pool", "icon": "fas fa-swimming-pool"}
                ],
                "average_rating": 4.2,
                "total_reviews": 156
            }
        ]
    },
    "meta": {
        "pagination": {
            "current_page": 1,
            "total_pages": 10,
            "per_page": 20,
            "total": 200,
            "from": 1,
            "to": 20
        }
    }
}
```

### Get Hotel Details
**GET** `/hotels/{id}`

Get detailed information about a specific hotel.

**Response:**
```json
{
    "success": true,
    "data": {
        "hotel": {
            "id": 1,
            "name": "Grand Hotel NYC",
            "slug": "grand-hotel-nyc",
            "description": "Luxury hotel in the heart of Manhattan...",
            "address": "123 Broadway, New York, NY 10001",
            "city": "New York",
            "state": "NY",
            "country": "USA",
            "postal_code": "10001",
            "phone": "+1234567890",
            "email": "info@grandhotel.com",
            "website": "https://grandhotel.com",
            "star_rating": 4,
            "check_in_time": "14:00:00",
            "check_out_time": "11:00:00",
            "policies": "Cancellation policy: 24 hours...",
            "latitude": 40.7589,
            "longitude": -73.9851,
            "images": [
                {
                    "id": 1,
                    "image_path": "/storage/hotels/hotel-1-main.jpg",
                    "alt_text": "Hotel exterior",
                    "is_primary": true
                },
                {
                    "id": 2,
                    "image_path": "/storage/hotels/hotel-1-lobby.jpg",
                    "alt_text": "Hotel lobby",
                    "is_primary": false
                }
            ],
            "amenities": [
                {"id": 1, "name": "WiFi", "icon": "fas fa-wifi"},
                {"id": 2, "name": "Swimming Pool", "icon": "fas fa-swimming-pool"}
            ],
            "rooms": [
                {
                    "id": 1,
                    "name": "Deluxe Room",
                    "type": "deluxe",
                    "capacity": 2,
                    "base_price": 150.00,
                    "area": 25.5,
                    "available": true
                }
            ],
            "reviews": {
                "average_rating": 4.2,
                "total_reviews": 156,
                "rating_breakdown": {
                    "5": 45,
                    "4": 78,
                    "3": 25,
                    "2": 6,
                    "1": 2
                }
            }
        }
    }
}
```

### Create Hotel (Hotel Manager)
**POST** `/hotels`

Create a new hotel (requires hotel_manager role).

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "name": "My Hotel",
    "description": "A wonderful place to stay",
    "address": "123 Main St, City, State",
    "city": "City Name",
    "state": "State Name",
    "country": "Country Name",
    "postal_code": "12345",
    "phone": "+1234567890",
    "email": "hotel@example.com",
    "website": "https://myhotel.com",
    "star_rating": 4,
    "check_in_time": "14:00",
    "check_out_time": "11:00",
    "policies": "Hotel policies text...",
    "amenities": [1, 2, 3] // amenity IDs
}
```

**Response:**
```json
{
    "success": true,
    "message": "Hotel created successfully and submitted for approval",
    "data": {
        "hotel": {
            "id": 2,
            "name": "My Hotel",
            "slug": "my-hotel",
            "status": "pending",
            "created_at": "2025-09-14T10:30:00Z"
        }
    }
}
```

### Update Hotel
**PUT** `/hotels/{id}`

Update hotel information (hotel owner or admin).

**Headers:** `Authorization: Bearer {token}`

**Request Body:** (Same as create hotel)

**Response:**
```json
{
    "success": true,
    "message": "Hotel updated successfully",
    "data": {
        "hotel": {
            "id": 1,
            "name": "Updated Hotel Name",
            "updated_at": "2025-09-14T10:30:00Z"
        }
    }
}
```

---

## 🛏️ Room Endpoints

### List Rooms
**GET** `/hotels/{hotel_id}/rooms`

Get available rooms for a hotel.

**Query Parameters:**
- `check_in` (date) - Check-in date
- `check_out` (date) - Check-out date
- `guests` (integer) - Number of guests
- `type` (string) - Room type filter
- `min_price` (decimal) - Minimum price
- `max_price` (decimal) - Maximum price

**Response:**
```json
{
    "success": true,
    "data": {
        "rooms": [
            {
                "id": 1,
                "hotel_id": 1,
                "name": "Deluxe Room",
                "type": "deluxe",
                "description": "Spacious room with city view",
                "capacity": 2,
                "base_price": 150.00,
                "area": 25.5,
                "bed_type": "King Bed",
                "quantity": 10,
                "available_quantity": 3,
                "images": [
                    {
                        "id": 3,
                        "image_path": "/storage/rooms/room-1-main.jpg",
                        "alt_text": "Deluxe room",
                        "is_primary": true
                    }
                ],
                "amenities": [
                    {"id": 4, "name": "Air Conditioning", "icon": "fas fa-snowflake"},
                    {"id": 5, "name": "TV", "icon": "fas fa-tv"}
                ]
            }
        ]
    }
}
```

### Get Room Details
**GET** `/rooms/{id}`

Get detailed room information.

**Response:**
```json
{
    "success": true,
    "data": {
        "room": {
            "id": 1,
            "hotel": {
                "id": 1,
                "name": "Grand Hotel NYC",
                "address": "123 Broadway, New York, NY"
            },
            "name": "Deluxe Room",
            "type": "deluxe",
            "description": "Spacious room with city view...",
            "capacity": 2,
            "base_price": 150.00,
            "area": 25.5,
            "bed_type": "King Bed",
            "images": [...],
            "amenities": [...],
            "availability": {
                "2025-09-15": {"available": true, "price": 150.00},
                "2025-09-16": {"available": true, "price": 150.00}
            }
        }
    }
}
```

### Create Room (Hotel Manager)
**POST** `/hotels/{hotel_id}/rooms`

Add a new room to hotel.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "name": "Executive Suite",
    "description": "Luxurious suite with panoramic views",
    "type": "suite",
    "capacity": 4,
    "base_price": 300.00,
    "area": 45.0,
    "bed_type": "King + Sofa Bed",
    "quantity": 5,
    "amenities": [4, 5, 6]
}
```

---

## 📅 Booking Endpoints

### Create Booking
**POST** `/bookings`

Create a new booking.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "hotel_id": 1,
    "room_id": 1,
    "check_in_date": "2025-10-15",
    "check_out_date": "2025-10-18",
    "adults": 2,
    "children": 1,
    "guest_name": "John Doe",
    "guest_email": "john@example.com",
    "guest_phone": "+1234567890",
    "special_requests": "Late check-in requested"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Booking created successfully",
    "data": {
        "booking": {
            "id": 1,
            "booking_number": "BK-2025-001",
            "hotel": {
                "id": 1,
                "name": "Grand Hotel NYC"
            },
            "room": {
                "id": 1,
                "name": "Deluxe Room"
            },
            "check_in_date": "2025-10-15",
            "check_out_date": "2025-10-18",
            "nights": 3,
            "adults": 2,
            "children": 1,
            "room_price": 150.00,
            "total_amount": 450.00,
            "tax_amount": 45.00,
            "status": "pending",
            "guest_name": "John Doe",
            "guest_email": "john@example.com",
            "guest_phone": "+1234567890",
            "created_at": "2025-09-14T10:30:00Z"
        }
    }
}
```

### List User Bookings
**GET** `/bookings`

Get current user's bookings.

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status` (string) - Filter by status
- `per_page` (integer) - Results per page
- `page` (integer) - Page number

**Response:**
```json
{
    "success": true,
    "data": {
        "bookings": [
            {
                "id": 1,
                "booking_number": "BK-2025-001",
                "hotel": {
                    "id": 1,
                    "name": "Grand Hotel NYC",
                    "image": "/storage/hotels/hotel-1-main.jpg"
                },
                "room": {
                    "id": 1,
                    "name": "Deluxe Room"
                },
                "check_in_date": "2025-10-15",
                "check_out_date": "2025-10-18",
                "nights": 3,
                "total_amount": 450.00,
                "status": "confirmed",
                "created_at": "2025-09-14T10:30:00Z"
            }
        ]
    },
    "meta": {
        "pagination": {...}
    }
}
```

### Get Booking Details
**GET** `/bookings/{id}`

Get detailed booking information.

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
    "success": true,
    "data": {
        "booking": {
            "id": 1,
            "booking_number": "BK-2025-001",
            "hotel": {
                "id": 1,
                "name": "Grand Hotel NYC",
                "address": "123 Broadway, New York, NY",
                "phone": "+1234567890",
                "email": "info@grandhotel.com"
            },
            "room": {
                "id": 1,
                "name": "Deluxe Room",
                "type": "deluxe",
                "capacity": 2
            },
            "check_in_date": "2025-10-15",
            "check_out_date": "2025-10-18",
            "nights": 3,
            "adults": 2,
            "children": 1,
            "room_price": 150.00,
            "total_amount": 450.00,
            "tax_amount": 45.00,
            "status": "confirmed",
            "guest_name": "John Doe",
            "guest_email": "john@example.com",
            "guest_phone": "+1234567890",
            "special_requests": "Late check-in requested",
            "payments": [
                {
                    "id": 1,
                    "amount": 450.00,
                    "payment_method": "stripe",
                    "status": "completed",
                    "payment_date": "2025-09-14T10:30:00Z"
                }
            ],
            "can_cancel": true,
            "cancellation_deadline": "2025-10-14T14:00:00Z"
        }
    }
}
```

### Cancel Booking
**DELETE** `/bookings/{id}`

Cancel a booking.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "cancellation_reason": "Change of plans"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Booking cancelled successfully",
    "data": {
        "booking": {
            "id": 1,
            "status": "cancelled",
            "cancelled_at": "2025-09-14T10:30:00Z",
            "refund_amount": 450.00
        }
    }
}
```

---

## 💳 Payment Endpoints

### Process Payment
**POST** `/payments`

Process payment for a booking.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "booking_id": 1,
    "payment_method": "stripe",
    "payment_method_id": "pm_1234567890", // Stripe Payment Method ID
    "amount": 450.00,
    "currency": "USD"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Payment processed successfully",
    "data": {
        "payment": {
            "id": 1,
            "booking_id": 1,
            "transaction_id": "pi_1234567890",
            "amount": 450.00,
            "currency": "USD",
            "status": "completed",
            "payment_date": "2025-09-14T10:30:00Z"
        },
        "booking": {
            "id": 1,
            "status": "confirmed"
        }
    }
}
```

### Get Payment Details
**GET** `/payments/{id}`

Get payment information.

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
    "success": true,
    "data": {
        "payment": {
            "id": 1,
            "booking": {
                "id": 1,
                "booking_number": "BK-2025-001"
            },
            "amount": 450.00,
            "currency": "USD",
            "payment_method": "stripe",
            "transaction_id": "pi_1234567890",
            "status": "completed",
            "payment_date": "2025-09-14T10:30:00Z"
        }
    }
}
```

---

## ⭐ Review Endpoints

### Create Review
**POST** `/reviews`

Create a review for a completed booking.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "booking_id": 1,
    "rating": 5,
    "title": "Excellent stay!",
    "comment": "The hotel exceeded our expectations...",
    "cleanliness_rating": 5,
    "service_rating": 4,
    "location_rating": 5,
    "value_rating": 4
}
```

**Response:**
```json
{
    "success": true,
    "message": "Review submitted successfully",
    "data": {
        "review": {
            "id": 1,
            "booking_id": 1,
            "hotel_id": 1,
            "rating": 5,
            "title": "Excellent stay!",
            "comment": "The hotel exceeded our expectations...",
            "status": "pending",
            "created_at": "2025-09-14T10:30:00Z"
        }
    }
}
```

### List Hotel Reviews
**GET** `/hotels/{hotel_id}/reviews`

Get reviews for a hotel.

**Query Parameters:**
- `per_page` (integer) - Results per page
- `page` (integer) - Page number
- `sort` (string) - Sort by: newest, oldest, rating_high, rating_low

**Response:**
```json
{
    "success": true,
    "data": {
        "reviews": [
            {
                "id": 1,
                "user": {
                    "name": "John Doe",
                    "avatar": "/storage/avatars/user-1.jpg"
                },
                "rating": 5,
                "title": "Excellent stay!",
                "comment": "The hotel exceeded our expectations...",
                "cleanliness_rating": 5,
                "service_rating": 4,
                "location_rating": 5,
                "value_rating": 4,
                "created_at": "2025-09-14T10:30:00Z",
                "admin_response": null
            }
        ],
        "summary": {
            "average_rating": 4.2,
            "total_reviews": 156,
            "rating_breakdown": {
                "5": 45,
                "4": 78,
                "3": 25,
                "2": 6,
                "1": 2
            }
        }
    },
    "meta": {
        "pagination": {...}
    }
}
```

---

## 🛡️ Admin Endpoints

### List All Hotels (Admin)
**GET** `/admin/hotels`

Get all hotels with admin privileges.

**Headers:** `Authorization: Bearer {admin_token}`

**Query Parameters:**
- `status` (string) - Filter by status
- `per_page` (integer) - Results per page
- `page` (integer) - Page number

**Response:**
```json
{
    "success": true,
    "data": {
        "hotels": [
            {
                "id": 1,
                "name": "Grand Hotel NYC",
                "user": {
                    "id": 2,
                    "name": "Hotel Manager",
                    "email": "manager@grandhotel.com"
                },
                "status": "approved",
                "created_at": "2025-09-14T10:30:00Z",
                "total_rooms": 50,
                "total_bookings": 1250
            }
        ]
    }
}
```

### Approve Hotel
**PUT** `/admin/hotels/{id}/approve`

Approve a pending hotel.

**Headers:** `Authorization: Bearer {admin_token}`

**Response:**
```json
{
    "success": true,
    "message": "Hotel approved successfully",
    "data": {
        "hotel": {
            "id": 1,
            "status": "approved",
            "updated_at": "2025-09-14T10:30:00Z"
        }
    }
}
```

### Reject Hotel
**PUT** `/admin/hotels/{id}/reject`

Reject a pending hotel.

**Headers:** `Authorization: Bearer {admin_token}`

**Request Body:**
```json
{
    "rejection_reason": "Incomplete documentation provided"
}
```

### System Analytics
**GET** `/admin/analytics`

Get system-wide analytics.

**Headers:** `Authorization: Bearer {admin_token}`

**Query Parameters:**
- `period` (string) - daily, weekly, monthly, yearly
- `start_date` (date) - Start date for custom period
- `end_date` (date) - End date for custom period

**Response:**
```json
{
    "success": true,
    "data": {
        "overview": {
            "total_hotels": 150,
            "total_users": 5000,
            "total_bookings": 25000,
            "total_revenue": 2500000.00,
            "pending_hotels": 5
        },
        "trends": {
            "bookings_trend": [
                {"date": "2025-09-01", "count": 45},
                {"date": "2025-09-02", "count": 52}
            ],
            "revenue_trend": [
                {"date": "2025-09-01", "amount": 4500.00},
                {"date": "2025-09-02", "amount": 5200.00}
            ]
        },
        "top_hotels": [
            {
                "id": 1,
                "name": "Grand Hotel NYC",
                "total_bookings": 150,
                "total_revenue": 45000.00
            }
        ]
    }
}
```

---

## 🔍 Search Endpoints

### Global Search
**GET** `/search`

Search across hotels, locations, and amenities.

**Query Parameters:**
- `query` (string) - Search query
- `type` (string) - Filter by type: hotels, locations
- `per_page` (integer) - Results per page

**Response:**
```json
{
    "success": true,
    "data": {
        "hotels": [...],
        "locations": [
            {
                "city": "New York",
                "state": "NY",
                "country": "USA",
                "hotel_count": 25
            }
        ],
        "suggestions": [
            "New York Hotels",
            "Hotels with Swimming Pool",
            "Luxury Hotels"
        ]
    }
}
```

---

## 📱 Utility Endpoints

### Get Amenities
**GET** `/amenities`

Get list of available amenities.

**Response:**
```json
{
    "success": true,
    "data": {
        "amenities": [
            {
                "id": 1,
                "name": "WiFi",
                "slug": "wifi",
                "icon": "fas fa-wifi",
                "type": "both"
            },
            {
                "id": 2,
                "name": "Swimming Pool",
                "slug": "swimming-pool",
                "icon": "fas fa-swimming-pool",
                "type": "hotel"
            }
        ]
    }
}
```

### Get Locations
**GET** `/locations`

Get popular destinations.

**Response:**
```json
{
    "success": true,
    "data": {
        "locations": [
            {
                "city": "New York",
                "state": "NY",
                "country": "USA",
                "hotel_count": 25,
                "image": "/storage/locations/new-york.jpg"
            }
        ]
    }
}
```

---

## 🚦 Rate Limiting

API endpoints are rate limited as follows:
- **Authentication**: 5 requests per minute
- **Hotel Search**: 60 requests per minute
- **Booking Creation**: 10 requests per minute
- **General API**: 100 requests per minute

Rate limit headers are included in responses:
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1631234567
```

---

## 🔔 Webhooks

### Booking Events
Configure webhooks to receive real-time booking updates:

**Webhook URL**: `POST https://your-app.com/webhooks/bookings`

**Events:**
- `booking.created`
- `booking.confirmed`
- `booking.cancelled`
- `payment.completed`
- `payment.failed`

**Payload Example:**
```json
{
    "event": "booking.confirmed",
    "data": {
        "booking": {
            "id": 1,
            "booking_number": "BK-2025-001",
            "hotel_id": 1,
            "status": "confirmed"
        }
    },
    "timestamp": "2025-09-14T10:30:00Z"
}
```

---

*API Version: 1.0.0*
*Last Updated: September 14, 2025*