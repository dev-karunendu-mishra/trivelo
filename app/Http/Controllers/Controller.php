<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Trivelo Hotel Booking API",
 *     version="1.0.0",
 *     description="Comprehensive hotel booking system API with multi-role authentication",
 *
 *     @OA\Contact(
 *         email="support@trivelo.com"
 *     ),
 *
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="JWT Authorization header using the Bearer scheme"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Authentication and user management endpoints"
 * )
 * @OA\Tag(
 *     name="Hotels",
 *     description="Hotel management endpoints"
 * )
 * @OA\Tag(
 *     name="Rooms",
 *     description="Room management endpoints"
 * )
 * @OA\Tag(
 *     name="Bookings",
 *     description="Booking management endpoints"
 * )
 * @OA\Tag(
 *     name="Payments",
 *     description="Payment processing endpoints"
 * )
 * @OA\Tag(
 *     name="Reviews",
 *     description="Review and rating endpoints"
 * )
 * @OA\Tag(
 *     name="Admin",
 *     description="Admin-only endpoints"
 * )
 */
abstract class Controller
{
    //
}
