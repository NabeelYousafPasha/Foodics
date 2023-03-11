<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param string $message
     * @param array $data
     * @param int $responseCode
     *
     * @return JsonResponse
     */
    public function successResponse(string $message, array $data, int $responseCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $responseCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message
     * @param int $responseCode
     * @param array $data
     *
     * @return JsonResponse
     */
    public function errorResponse(string $message, int $responseCode, array $data = []): JsonResponse
    {
        $response = [
            'status' => 'Error',
            'message' => $message,
            'data' => $data,
        ];

        if (empty($data)) {
            unset($response['data']);
        }

        return response()->json($response, $responseCode);
    }
}
