<?php

namespace Aldeebhasan\NaiveCrud\Http\Controllers;

use Aldeebhasan\NaiveCrud\Http\Controllers\Traits\IndexTrait;
use Aldeebhasan\NaiveCrud\Http\Controllers\Traits\ResponseTrait;
use Aldeebhasan\NaiveCrud\Http\Controllers\Traits\SearchTrait;
use Aldeebhasan\NaiveCrud\Http\Controllers\Traits\ShowTrait;
use Aldeebhasan\NaiveCrud\Http\Controllers\Traits\StoreTrait;
use Aldeebhasan\NaiveCrud\Http\Controllers\Traits\UpdateTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;

abstract class CrudController extends Controller
{
    use IndexTrait, ShowTrait, StoreTrait, UpdateTrait, ResponseTrait, SearchTrait;

    protected string $model;

    protected string $modelResource;

    protected ?User $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->afterConstructHook($this);

            return $next($request);
        });
    }

    public function afterConstructHook(self $instance): void
    {
        // do what you want
    }

    public function globalQuery(Builder $query): Builder
    {
        return $query;
    }
}
