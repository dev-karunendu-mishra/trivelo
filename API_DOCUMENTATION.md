# Trivelo Hotel Booking API Documentation

## Overview

The Trivelo Hotel Booking API is a comprehensive RESTful API built with Laravel 12 that provides a complete hotel booking system with role-based access control (RBAC), advanced search and filtering capabilities, availability checking, and booking management.

## API Documentation

### Swagger/OpenAPI Documentation

The API is fully documented using OpenAPI 3.0 specification with Swagger UI for interactive documentation.

**Access the API Documentation:**
- **Local Development**: http://localhost:8000/api/documentation
- **Production**: Replace with your production domain

### Features

- 🏨 **Hotel Management**: Complete CRUD operations for hotels
- 🏠 **Room Management**: Room inventory with availability tracking
- 📅 **Booking System**: Full booking lifecycle management
- 👥 **Role-Based Access Control**: Super Admin, Hotel Manager, Customer roles
- 🔍 **Advanced Search**: Filter by location, price, amenities, ratings
- 💳 **Payment Integration**: Payment tracking and management
- ⭐ **Review System**: Customer reviews and ratings
- 🔐 **Authentication**: Laravel Sanctum token-based authentication

## Quick Start

### Authentication

All API endpoints (except public ones) require authentication using Laravel Sanctum tokens.

**Get Authentication Token:**
```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Use Token in Requests:**
```bash
Authorization: Bearer {your-token}
```

### Example API Calls

#### 1. Search Hotels
```bash
GET /api/hotels?city=New York&star_rating=4&min_price=100&max_price=300
```

#### 2. Check Room Availability
```bash
POST /api/rooms/1/check-availability
Content-Type: application/json

{
  "check_in_date": "2024-12-01",
  "check_out_date": "2024-12-05"
}
```

#### 3. Create Booking
```bash
POST /api/bookings
Content-Type: application/json
Authorization: Bearer {your-token}

{
  "room_id": 1,
  "check_in_date": "2024-12-01",
  "check_out_date": "2024-12-05",
  "guests_count": 2,
  "special_requests": "Late check-in requested"
}
```

## API Endpoints Overview

### Authentication Endpoints
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `POST /api/auth/refresh` - Refresh token
- `GET /api/auth/me` - Get current user info

### Hotel Endpoints
- `GET /api/hotels` - List hotels with filtering
- `POST /api/hotels` - Create hotel (Hotel Manager only)
- `GET /api/hotels/{id}` - Get hotel details
- `PUT /api/hotels/{id}` - Update hotel (Hotel Manager/Admin only)
- `DELETE /api/hotels/{id}` - Delete hotel (Admin only)

### Room Endpoints
- `GET /api/hotels/{hotel}/rooms` - List hotel rooms
- `POST /api/hotels/{hotel}/rooms` - Create room (Hotel Manager only)
- `GET /api/rooms/{id}` - Get room details
- `PUT /api/rooms/{id}` - Update room (Hotel Manager only)
- `DELETE /api/rooms/{id}` - Delete room (Hotel Manager only)
- `POST /api/rooms/{id}/check-availability` - Check availability

### Booking Endpoints
- `GET /api/bookings` - List user bookings
- `POST /api/bookings` - Create booking (Customer only)
- `GET /api/bookings/{id}` - Get booking details
- `PUT /api/bookings/{id}` - Update booking
- `DELETE /api/bookings/{id}` - Cancel booking

### Admin Endpoints
- `GET /api/admin/hotels` - List all hotels (Admin only)
- `PUT /api/admin/hotels/{id}/status` - Update hotel status (Admin only)
- `GET /api/admin/users` - List all users (Admin only)
- `PUT /api/admin/users/{id}/role` - Update user role (Admin only)

## Data Models

### Hotel
```json
{
  "id": 1,
  "name": "Grand Plaza Hotel",
  "description": "Luxury hotel in city center",
  "email": "info@grandplaza.com",
  "phone": "+1234567890",
  "address": "123 Main Street",
  "city": "New York",
  "country": "USA",
  "star_rating": 4,
  "status": "approved",
  "amenities": [...],
  "rooms_count": 50,
  "average_rating": 4.5
}
```

### Room
```json
{
  "id": 1,
  "hotel_id": 1,
  "room_number": "101",
  "room_type": "deluxe",
  "price_per_night": 150.00,
  "capacity": 2,
  "is_available": true,
  "amenities": [...]
}
```

### Booking
```json
{
  "id": 1,
  "booking_reference": "BK-2024-001",
  "check_in_date": "2024-12-01",
  "check_out_date": "2024-12-05",
  "guests_count": 2,
  "total_amount": 660.00,
  "status": "confirmed",
  "payment_status": "paid"
}
```

## Response Format

All API responses follow a consistent format:

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    // Response data here
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "data": null,
  "errors": [
    "Detailed error messages"
  ]
}
```

### Paginated Response
```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 250,
    "last_page": 17
  }
}
```

## Status Codes

- `200 OK` - Request successful
- `201 Created` - Resource created successfully
- `400 Bad Request` - Invalid request data
- `401 Unauthorized` - Authentication required
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation errors
- `500 Internal Server Error` - Server error

## Error Handling

The API provides detailed error messages and validation feedback:

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

## Rate Limiting

API requests are rate limited to prevent abuse:
- **Authenticated users**: 60 requests per minute
- **Unauthenticated users**: 30 requests per minute

## Development

### Generate API Documentation

To regenerate the API documentation after making changes:

```bash
php artisan l5-swagger:generate
```

### Environment Setup

1. Copy `.env.example` to `.env`
2. Configure database settings
3. Run migrations: `php artisan migrate`
4. Seed database: `php artisan db:seed`
5. Generate application key: `php artisan key:generate`

### Testing the API

Use tools like Postman, Insomnia, or curl to test the API endpoints. The Swagger UI provides an interactive interface for testing directly in the browser.

## Support

For API support and questions:
- Email: api-support@trivelo.com
- Documentation: http://localhost:8000/api/documentation
- GitHub Issues: [Create Issue](https://github.com/your-org/trivelo/issues)

---

**Version**: 1.0.0  
**Last Updated**: September 2025  
**License**: MIT