<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    protected function success($message, $data = [], $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function fail($message, $data = [], $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function notFound(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => __('NaiveCrud::messages.notfound'),
            'data' => [],
        ], 404);
    }
}
