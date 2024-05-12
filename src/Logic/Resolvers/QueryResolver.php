<?php

namespace Aldeebhasan\NaiveCrud\Logic\Resolvers;

use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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

    /**
     * @param FilterUI|array<FilterUI> $filters
     */
    public function setFilters(FilterUI|array $filters): self
    {
        $this->filters = Arr::wrap($filters);

        return $this;
    }

    /**
     * @param SortUI|array<SortUI> $sorts
     */
    public function setSorters(SortUI|array $sorts): self
    {
        $this->sorters = Arr::wrap($sorts);

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
