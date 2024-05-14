<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Controllers;

use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Policies\BlogPolicy;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BlogController extends BaseController
{
    use ValidatesRequests;

    protected string $model = Blog::class;

    protected ?string $policy = BlogPolicy::class;

    protected function extraStoreData(): array
    {
        return [
            'user_id' => $this->resolveUser()->id,
        ];
    }
}
