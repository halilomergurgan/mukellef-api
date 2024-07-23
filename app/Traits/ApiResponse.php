<?php

namespace App\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * @param $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function jsonResponse($data = null, string $message = '', int $statusCode = 200): JsonResponse
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray(request());
        }

        return response()->json([
            'message' => $message,
            'status_code' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }
}
