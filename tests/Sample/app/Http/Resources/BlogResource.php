<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Resources;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class BlogResource extends BaseResource
{
    public function toIndexArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => str($this->title)->slug(),
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
