<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Controllers;

use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Policies\BlogPolicy;

class BlogController extends BaseController
{
    protected string $model = Blog::class;

    protected ?string $policy = BlogPolicy::class;
}
