<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseApiController extends Controller
{
    /**
     * Return a success JSON response.
     */
    protected function successResponse(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a paginated success response.
     */
    protected function paginatedResponse(AnonymousResourceCollection $collection, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $collection->resource->items(),
            'pagination' => [
                'current_page' => $collection->resource->currentPage(),
                'per_page' => $collection->resource->perPage(),
                'total' => $collection->resource->total(),
                'last_page' => $collection->resource->lastPage(),
                'from' => $collection->resource->firstItem(),
                'to' => $collection->resource->lastItem(),
                'has_more_pages' => $collection->resource->hasMorePages(),
            ],
        ]);
    }

    /**
     * Return a resource response.
     */
    protected function resourceResponse(JsonResource $resource, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resource,
        ], $code);
    }

    /**
     * Return a validation error response.
     */
    protected function validationErrorResponse(array $errors): JsonResponse
    {
        return $this->errorResponse('Validation failed', 422, $errors);
    }

    /**
     * Return a not found error response.
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Return an unauthorized error response.
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Return a forbidden error response.
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }

    /**
     * Return a server error response.
     */
    protected function serverErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }
}