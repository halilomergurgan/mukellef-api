<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * @param array|null $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function jsonResponse(?array $data, string $message = '', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status_code' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }
}
