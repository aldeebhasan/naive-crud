<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Controllers;

use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Requests\CommentRequest;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Resources\CommentResource;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Comment;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Policies\CommentPolicy;

class CommentController extends BaseController
{
    protected string $model = Comment::class;

    protected ?string $policy = CommentPolicy::class;

    protected ?string $modelRequestForm = CommentRequest::class;

    protected ?string $modelResource = CommentResource::class;

    protected bool $exportAllShouldQueue = true;

    protected function extraStoreData(): array
    {
        return [
            'user_id' => $this->resolveUser()->id,
        ];
    }
}
