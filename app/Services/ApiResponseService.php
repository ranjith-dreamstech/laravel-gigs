<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ApiResponseService
{
    /**
     * Return a success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public static function success($data = null, string $message = 'Success', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ], $code);
    }

    /**
     * Return an error response
     *
     * @param string $message
     * @param mixed $errors
     * @param int $code
     * @return JsonResponse
     */
    public static function error(string $message, $errors = null, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a validation error response
     *
     * @param ValidationException $exception
     * @return JsonResponse
     */
    public static function validationError(ValidationException $exception): JsonResponse
    {
        return self::error(
            'Validation failed',
            $exception->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Return a not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Return an unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Return a forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_FORBIDDEN);
    }

    /**
     * Return a server error response
     *
     * @param string $message
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function serverError(string $message = 'Internal server error', $errors = null): JsonResponse
    {
        return self::error($message, $errors, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Return a paginated response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function paginated($data, string $message = 'Success'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'has_more_pages' => $data->hasMorePages(),
            ],
            'timestamp' => now()->toISOString(),
        ];

        return response()->json($response);
    }

    /**
     * Return a created response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return self::success($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Return a no content response
     *
     * @return JsonResponse
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Return a custom response
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param mixed $errors
     * @param int $code
     * @return JsonResponse
     */
    public static function custom(
        bool $success,
        string $message,
        $data = null,
        $errors = null,
        int $code = Response::HTTP_OK
    ): JsonResponse {
        $response = [
            'success' => $success,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a response with metadata
     *
     * @param mixed $data
     * @param array $meta
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public static function withMeta($data, array $meta = [], string $message = 'Success', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
            'timestamp' => now()->toISOString(),
        ], $code);
    }

    /**
     * Handle exception and return appropriate response
     *
     * @param \Exception $exception
     * @param bool $debug
     * @return JsonResponse
     */
    public static function exception(\Exception $exception, bool $debug = false): JsonResponse
    {
        $message = 'An error occurred';
        $errors = null;
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof ValidationException) {
            return self::validationError($exception);
        }

        if ($debug) {
            $errors = [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        // Handle specific exception types
        if (method_exists($exception, 'getStatusCode')) {
            $code = $exception->getStatusCode();
        }

        if ($exception->getMessage()) {
            $message = $exception->getMessage();
        }

        return self::error($message, $errors, $code);
    }

    /**
     * Legacy support for buildResponse function
     * 
     * @param int $code
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param mixed $error
     * @return array
     */
    public static function buildResponse(int $code, bool $success, string $message, $data = null, $error = null): array
    {
        $response = [
            'code' => $code,
            'success' => $success,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($error !== null) {
            $response['error'] = $error;
        }

        return $response;
    }
}