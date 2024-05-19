<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    protected function success(array $data = [], string $message = "", int $status = 200): JsonResponse
    {
        $message = $message ?: __('NaiveCrud::messages.success');
        return response()->json([
            'message' => $message,
            'data' => !empty($data) ? $data : null,
        ], $status);
    }

    protected function fail(string $message = "", int $status = 400, array $data = []): JsonResponse
    {
        $message = $message ?: __('NaiveCrud::messages.failed');
        return response()->json([
            'message' => $message,
            'data' => !empty($data) ? $data : null,
        ], $status);
    }

    protected function notFound(): JsonResponse
    {
        return $this->fail(message: __('NaiveCrud::messages.notfound'), status: 404);
    }
}
