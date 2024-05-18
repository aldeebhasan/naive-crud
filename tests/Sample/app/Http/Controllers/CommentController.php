<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Controllers;

use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Comment;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Policies\CommentPolicy;

class CommentController extends BaseController
{
    protected string $model = Comment::class;

    protected ?string $policy = CommentPolicy::class;
}
