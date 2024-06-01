<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Requests;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;

class CommentRequest extends BaseRequest
{
    public function storeRules(): array
    {
        return [
            'content' => 'required|string',
            'blog_id' => 'required|integer',
        ];
    }

    public function updateRules(): array
    {
        return [
            'content' => 'required|string',
            'blog_id' => 'required|integer',
        ];
    }

    public function toggleRules(): array
    {
        return [
            'active' => 'nullable|boolean',
        ];
    }
}
