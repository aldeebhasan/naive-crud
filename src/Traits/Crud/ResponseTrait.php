<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    protected function success(array $data = [], string $message = '', int $status = 200): Response|Responsable
    {
        $message = $message ?: __('NaiveCrud::messages.success');

        return response()->json([
            'message' => $message,
            'data' => ! empty($data) ? $data : null,
        ], $status);
    }

    protected function fail(string $message = '', int $status = 400, array $data = []): Response|Responsable
    {
        $message = $message ?: __('NaiveCrud::messages.failed');

        return response()->json([
            'message' => $message,
            'data' => ! empty($data) ? $data : null,
        ], $status);
    }

    protected function notFound(): Response|Responsable
    {
        return $this->fail(message: __('NaiveCrud::messages.notfound'), status: 404);
    }
}
