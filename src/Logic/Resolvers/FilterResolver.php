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

    protected array $filters;

    protected array $values;

    public function __construct(protected Request $request)
    {
        $this->values = $request->get('filters', []);
    }

    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }

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
            $fields = app($filter)->fields();
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
        /** @var FilterField $field */
        foreach ($fields as $field) {

            $value = $field->value ?? Arr::get($this->values, $field->field);

            if (empty($value)) continue;

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

    public function render(): array
    {
        $resultFields = [];
        foreach ($this->filters as $filter) {
            $fields = app($filter)->fields();
            foreach ($fields as $field) {
                $resultFields[$field->field] = $this->renderSingleField($field);
            }
        }

        return array_values($resultFields);
    }

    private function renderSingleField(FilterField $field): array
    {
        return [
            'type' => $field->type,
            'name' => $field->field,
            'url' => $field->resourceURL,
            'label' => $field->label ?? str($field->field)->title()->toString(),
            'value' => (string) ($field->value ?? Arr::get($this->values, $field->field)),
            'options' => $field->options,
        ];
    }
}
