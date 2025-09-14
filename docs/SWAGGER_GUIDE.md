# Swagger API Documentation Guide - Trivelo

## 🚀 Overview

Trivelo now includes **automatic API documentation** using **Laravel L5-Swagger** (OpenAPI/Swagger). This provides interactive API documentation that automatically updates as you define controllers, similar to NestJS decorators.

## 📋 What's Included

✅ **Laravel L5-Swagger** package installed and configured
✅ **Interactive Swagger UI** accessible at `/api/documentation`
✅ **Automatic documentation generation** from code annotations
✅ **Example controllers** with complete Swagger annotations
✅ **Comprehensive schema definitions** for all data models
✅ **Authentication support** with Bearer token

## 🌐 Accessing Documentation

### Development
- **URL**: `http://localhost:8000/api/documentation`
- **JSON**: `http://localhost:8000/docs/api-docs.json`
- **YAML**: `http://localhost:8000/docs/api-docs.yaml`

### Production
- **URL**: `https://your-domain.com/api/documentation`

## 📝 How It Works

### 1. **Automatic Generation**
Just like NestJS, documentation is generated automatically from your code annotations:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HotelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="List hotels",
     *     description="Get list of approved hotels with filtering",
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="Filter by city",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hotels retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Hotel")
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        // Your implementation here
    }
}
```

### 2. **Schema Definitions**
Reusable schemas are defined in `SwaggerSchemas.php`:

```php
/**
 * @OA\Schema(
 *     schema="Hotel",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Grand Hotel"),
 *     @OA\Property(property="star_rating", type="integer", example=4)
 * )
 */
```

### 3. **Authentication**
JWT Bearer token authentication is pre-configured:

```php
/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
```

## 🛠️ Usage Commands

### Generate Documentation
```bash
# Generate/regenerate documentation
php artisan l5-swagger:generate

# This runs automatically when you change annotations
```

### View Documentation
```bash
# Start development server
php artisan serve

# Visit: http://localhost:8000/api/documentation
```

## 📊 Current API Coverage

### ✅ Implemented Examples

#### **Authentication Endpoints**
- `POST /api/auth/login` - User login with credentials
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout (protected)

#### **Hotel Endpoints**
- `GET /api/hotels` - List hotels with advanced filtering
- `GET /api/hotels/{id}` - Get hotel details
- `POST /api/hotels` - Create hotel (hotel manager only)

#### **Schemas Defined**
- `Hotel` - Basic hotel information
- `HotelDetail` - Detailed hotel with rooms and reviews
- `Room` - Room information with amenities
- `Booking` - Booking details and status
- `Payment` - Payment transaction data
- `Review` - Customer reviews and ratings
- `Amenity` - Hotel/room amenities

## 🎯 Adding New Endpoints

### Step 1: Create Controller with Annotations

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="Create booking",
     *     description="Create a new hotel reservation",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"hotel_id", "room_id", "check_in_date", "check_out_date"},
     *             @OA\Property(property="hotel_id", type="integer", example=1),
     *             @OA\Property(property="room_id", type="integer", example=1),
     *             @OA\Property(property="check_in_date", type="string", format="date", example="2025-10-15"),
     *             @OA\Property(property="check_out_date", type="string", format="date", example="2025-10-18"),
     *             @OA\Property(property="adults", type="integer", example=2),
     *             @OA\Property(property="children", type="integer", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Booking")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        // Your implementation here
        return response()->json(['message' => 'Booking created']);
    }
}
```

### Step 2: Add Route

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('bookings', [BookingController::class, 'store']);
});
```

### Step 3: Regenerate Documentation

```bash
php artisan l5-swagger:generate
```

**That's it!** Your new endpoint automatically appears in the Swagger UI with interactive testing capabilities.

## 🔧 Advanced Features

### **Request Validation**
```php
/**
 * @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *         required={"email", "password"},
 *         @OA\Property(
 *             property="email",
 *             type="string",
 *             format="email",
 *             example="user@example.com",
 *             description="User's email address"
 *         ),
 *         @OA\Property(
 *             property="password",
 *             type="string",
 *             format="password",
 *             minLength=8,
 *             example="password123"
 *         )
 *     )
 * )
 */
```

### **Complex Responses**
```php
/**
 * @OA\Response(
 *     response=200,
 *     description="Success response with pagination",
 *     @OA\JsonContent(
 *         @OA\Property(property="success", type="boolean", example=true),
 *         @OA\Property(
 *             property="data",
 *             type="object",
 *             @OA\Property(
 *                 property="hotels",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Hotel")
 *             )
 *         ),
 *         @OA\Property(
 *             property="meta",
 *             type="object",
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(property="total", type="integer", example=100)
 *         )
 *     )
 * )
 */
```

### **File Uploads**
```php
/**
 * @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *         mediaType="multipart/form-data",
 *         @OA\Schema(
 *             @OA\Property(property="name", type="string", example="Hotel Name"),
 *             @OA\Property(
 *                 property="image",
 *                 type="string",
 *                 format="binary",
 *                 description="Hotel main image"
 *             )
 *         )
 *     )
 * )
 */
```

## 📋 Best Practices

### **1. Consistent Response Format**
Always use the same response structure:

```php
/**
 * @OA\JsonContent(
 *     @OA\Property(property="success", type="boolean"),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="data", type="object"),
 *     @OA\Property(property="errors", type="object")
 * )
 */
```

### **2. Use Schema References**
Define reusable schemas and reference them:

```php
// Instead of repeating the same structure
@OA\JsonContent(ref="#/components/schemas/Hotel")

// Rather than defining it inline each time
```

### **3. Add Examples**
Always include realistic examples:

```php
@OA\Property(property="email", type="string", example="john@example.com")
```

### **4. Security Annotations**
Mark protected endpoints:

```php
/**
 * @OA\Post(
 *     // ... other annotations
 *     security={{"bearerAuth":{}}}
 * )
 */
```

## 🎨 Customization

### **Swagger UI Theme**
Modify `config/l5-swagger.php` to customize appearance:

```php
'ui' => [
    'display' => [
        'dark_theme' => false,
        'doc_expansion' => 'none',
        'filter' => true,
    ],
],
```

### **Environment Variables**
Add to `.env`:

```env
L5_SWAGGER_USE_ABSOLUTE_PATH=true
L5_FORMAT_TO_USE_FOR_DOCS=json
```

## 🚀 Development Workflow

### **Daily Development**
1. Write controller method
2. Add Swagger annotations
3. Documentation updates automatically!
4. Test in Swagger UI

### **Before Deployment**
```bash
# Generate final documentation
php artisan l5-swagger:generate

# Verify all endpoints are documented
curl http://localhost:8000/docs/api-docs.json
```

## 🔍 Testing in Swagger UI

### **Interactive Testing**
1. Visit `/api/documentation`
2. Click "Authorize" button
3. Enter your JWT token: `Bearer your_token_here`
4. Test any endpoint directly in the browser!

### **Example JWT Token Flow**
1. Use `/api/auth/login` endpoint
2. Copy the returned token
3. Click "Authorize" and paste token
4. Now test protected endpoints

## 📈 Benefits Over Static Documentation

### **✅ Always Up-to-Date**
- Documentation updates automatically with code changes
- No manual maintenance required
- Always reflects current API state

### **✅ Interactive Testing**
- Test endpoints directly in browser
- No need for Postman or curl
- Real-time validation

### **✅ Developer Friendly**
- Similar to NestJS decorators approach
- Code and docs in same place
- IDE autocompletion support

### **✅ Client Generation**
- Generate client SDKs automatically
- Support for multiple languages
- Always in sync with API

---

## 🎯 Next Steps

1. **Add More Endpoints**: Follow the pattern for bookings, payments, reviews
2. **Error Schemas**: Define common error response schemas
3. **Versioning**: Configure API versioning in Swagger
4. **Testing**: Add automated tests that validate Swagger documentation

Your API documentation is now as powerful and automatic as NestJS! 🚀

---

*Access your live documentation at: http://localhost:8000/api/documentation*