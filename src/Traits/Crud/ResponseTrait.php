<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    protected function success(string $message, array $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => ! empty($data) ? $data : null,
        ], $status);
    }

    protected function fail(string $message, array $data = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => ! empty($data) ? $data : null,
        ], $status);
    }

    protected function notFound(): JsonResponse
    {
        return $this->fail(__('NaiveCrud::messages.notfound'), status: 404);
    }
}
