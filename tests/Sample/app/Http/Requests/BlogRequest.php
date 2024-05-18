<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Requests;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;

class BlogRequest extends BaseRequest
{
    public function storeRules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|string',
        ];
    }

    public function updateRules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ];
    }

    public function toggleRules(): array
    {
        return [
            'active' => 'nullable|boolean',
        ];
    }
}
