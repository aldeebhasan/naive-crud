<?php

namespace Aldeebhasan\NaiveCrud\Http\Controllers;

use _PHPStan_e956fad2e\Symfony\Component\Console\Exception\LogicException;
use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\Traits\Crud\DeleteTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ExportTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\HooksTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ImportTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\IndexTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ResponseTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\SearchTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ShowTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\StoreTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\UpdateTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;

abstract class CrudController extends Controller
{
    use IndexTrait, ShowTrait, StoreTrait, UpdateTrait, ResponseTrait, SearchTrait, HooksTrait, DeleteTrait, ImportTrait, ExportTrait;

    protected string $model;

    protected string $modelResource;

    protected ?User $user;

    /**
     * @var array<FilterUI>$ filters
     * @var array<SortUI>$
     */
    protected array $filters = [];

    protected array $sorters = [];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->afterConstructHook($this);

            return $next($request);
        });

        throw_if(empty($this->model), LogicException::class, 'Model need to be defined');
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
