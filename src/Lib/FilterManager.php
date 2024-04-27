<?php

namespace Aldeebhasan\NaiveCrud\Lib;

use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**@method  static FilterManager make(Request $request) */
class FilterManager
{
    use Makable;

    public function __construct(protected Request $request)
    {
    }

    protected array $filters;

    /**
     * @param FilterUI|array<FilterUI> $filters
     * @return FilterManager
     */
    public function setFilters(FilterUI|array $filters): self
    {
        $this->filters = Arr::wrap($filters);

        return $this;
    }

    public function apply(Builder $query): Builder
    {
        foreach ($this->filters as $filter) {
            $fields = $filter->fields();
            $this->handleFields($query, $fields);

        }

        return $query;
    }

    private function handleFields(Builder $query, array $fields): void
    {
        /*
         * $field = [
         *           'field'=>'param',                     // (required)
         *           'column'=>'column',                   // default: $field['field'] (optional)
         *           'operator'=>'operator',               // default: '=' (optional)
         *           'callback'=>'fn($query, $value)=>{}', // custom function (optional)
         *           'value'=>'value',                     // default from request (optional)
         *        ]
         */
        foreach ($fields as $field) {

            $param = $field['field'];
            $value = $field['value'] ?? $this->request->get($param);
            $column = $field['column'] ?? $param;
            $operator = $field['operator'] ?? '=';
            $callback = $field['callback'] ?? null;

            $relation = null;
            if (strpos('.', $column) !== false) {
                [$relation, $column] = explode('.', $column);
            }

            $value = match ($operator) {
                'like' => '%'.$value.'%',
                'like%' => $value.'%',
                '%like' => '%'.$value,
                default => $value
            };
            if (! empty($callback) && is_callable($callback)) {
                call_user_func($callback, $query, $value);
            } elseif (! empty($relation) && is_string($relation)) {
                $query->whereHas($relation, function ($q) use ($column, $operator, $value) {
                    $q->where($column, $operator, $value);
                });
            } else {
                $query->where($column, $operator, $value);
            }
        }

    }
}
