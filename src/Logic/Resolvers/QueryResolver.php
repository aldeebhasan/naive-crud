<?php

namespace Aldeebhasan\NaiveCrud\Logic\Resolvers;

use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**@method  static QueryResolver make(string $modelClass, callable $baseQueryFn) */
class QueryResolver
{
    use Makable;

    protected Request $request;

    protected string $modelClass;

    protected Builder $query;

    protected array $filters = [];

    protected array $sorters = [];

    protected mixed $baseQueryFn;

    protected mixed $extendedQueryFn;

    protected array $extendedQueryArgs;

    public function __construct(string $modelClass, callable $baseQueryFn)
    {
        $this->modelClass = $modelClass;
        $this->baseQueryFn = $baseQueryFn;
        $this->query = $this->getModelQuery();
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function setSorters(array $sorters): self
    {
        $this->sorters = $sorters;

        return $this;
    }

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function setExtendQuery(callable $extendedQueryFN, ...$args): self
    {
        $this->extendedQueryFn = $extendedQueryFN;
        $this->extendedQueryArgs = $args;

        return $this;
    }

    private function getModelQuery(): Builder
    {
        return $this->modelClass::query();
    }

    public function build(): Builder
    {
        $this->request ??= \request();

        $this->query = call_user_func($this->baseQueryFn, $this->query);
        $this->query = call_user_func($this->extendedQueryFn, $this->query, ...$this->extendedQueryArgs);

        if ($this->filters) {
            $this->query = FilterResolver::make($this->request)->setFilters($this->filters)->apply($this->query);
        }
        if ($this->sorters) {
            $this->query = SortResolver::make($this->request)->setSorters($this->sorters)->apply($this->query);
        }

        return $this->query;
    }
}
