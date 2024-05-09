<?php

namespace Aldeebhasan\NaiveCrud\Http\Controllers;

use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\ComponentResolver;
use Aldeebhasan\NaiveCrud\Traits\Crud\AuthorizeTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\DeleteTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ExportTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\HooksTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ImportTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\IndexTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ResponseTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\SearchTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ShowTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\StoreTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ToggleTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\UpdateTrait;
use Aldeebhasan\NaiveCrud\Traits\QueryResolverTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use IndexTrait, ShowTrait, StoreTrait, UpdateTrait, ResponseTrait, SearchTrait,
        HooksTrait, DeleteTrait, ImportTrait, ExportTrait, AuthorizeTrait, ToggleTrait,
        QueryResolverTrait;

    protected string $model;

    protected ?string $policy;

    protected string $modelRequestForm;

    protected string $modelResource;

    protected ?User $user;

    protected ComponentResolver $componentsResolver;

    /**
     * @var array<FilterUI>$ filters
     * @var array<SortUI>$
     */
    protected array $filters = [];

    protected array $sorters = [];

    public function __construct()
    {
        throw_if(
            empty($this->model),
            \LogicException::class,
            'Model need to be defined'
        );

        $this->componentsResolver = ComponentResolver::make($this->model);

        $this->resolveComponents();
        $this->bindComponents();

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->afterConstructHook($this);

            return $next($request);
        });
    }

    private function resolveComponents(): void
    {

        $this->modelRequestForm = $this->componentsResolver->resolveRequestForm($this->modelRequestForm);
        $this->modelResource = $this->componentsResolver->resolveModelResource($this->modelResource);
    }

    /**
     * Binds resolved request class to the container.
     */
    protected function bindComponents(): void
    {
        $this->componentsResolver->bindRequestForm($this->modelRequestForm);

        if (! empty($this->policy)) {
            $this->componentsResolver->bindPolicy($this->policy);
        }
    }

    public function afterConstructHook(self $instance): void
    {
        // do what you want
    }

    public function baseQuery(Builder $query): Builder
    {
        return $query;
    }
}
