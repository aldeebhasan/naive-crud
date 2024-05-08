<?php

namespace Aldeebhasan\NaiveCrud\Logic\Resolvers;

use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\DTO\FilterField;
use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**@method  static FilterResolver make(Request $request) */
class FilterResolver
{
    use Makable;

    public function __construct(protected Request $request)
    {
    }

    protected array $filters;

    /**
     * @param FilterUI|array<FilterUI> $filters
     * @return FilterResolver
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

    /**
     * @param Builder $query
     * @param array<FilterField> $fields
     * @return void
     */
    private function handleFields(Builder $query, array $fields): void
    {
        /**@var FilterField $field*/
        foreach ($fields as $field) {

            $value = $field->value ?? $this->request->get($field->field);

            $value = match ($field->operator) {
                'like' => '%'.$value.'%',
                'like%' => $value.'%',
                '%like' => '%'.$value,
                default => $value
            };
            if (! empty($callback) && is_callable($callback)) {
                call_user_func($callback, $query, $value);
            } elseif (! empty($relation) && is_string($relation)) {
                $query->whereHas($relation, function ($q) use ($field, $value) {
                    $q->where($field->column, $field->operator, $value);
                });
            } else {
                $query->where($field->column, $field->operator, $value);
            }
        }

    }
}
