<?php

namespace Aldeebhasan\NaiveCrud\Logic\Resolvers;

use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\DTO\SortField;
use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**@method  static SortResolver make(Request $request) */
class SortResolver
{
    use Makable;

    protected array $sorts;

    protected array $values;

    public function __construct(protected Request $request)
    {
        $this->values = $request->get('sorts', []);
    }

    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @param SortUI|array<SortUI> $sorts
     * @return SortResolver
     */
    public function setSorters(SortUI|array $sorts): self
    {
        $this->sorts = Arr::wrap($sorts);

        return $this;
    }

    public function apply(Builder $query): Builder
    {
        foreach ($this->sorts as $sort) {
            $fields = $sort instanceof SortUI ? $sort->fields() : app($sort)->fields();
            $this->handleFields($query, $fields);
        }

        return $query;
    }

    private function handleFields(Builder $query, array $fields): void
    {
        /** @var SortField $field */
        foreach ($fields as $field) {

            if (! in_array($field->field, array_keys($this->values))) continue;

            $direction = Arr::get($this->values, $field->field) ?? $field->value;
            $direction = in_array($direction, ['asc', 'desc']) ? $direction : $field->value;

            if (! empty($field->callback) && is_callable($field->callback)) {
                call_user_func($field->callback, $query, $direction);
            } else {
                $query->orderBy($field->column, $direction);
            }
        }

    }

    public function render(): array
    {
        $resultFields = [];
        foreach ($this->sorts as $sort) {
            $fields = $sort instanceof SortUI ? $sort->fields() : app($sort)->fields();
            foreach ($fields as $field) {
                $resultFields[$field->field] = $this->renderSingleField($field);
            }
        }

        return array_values($resultFields);
    }

    private function renderSingleField(SortField $field): array
    {
        $direction = Arr::get($this->values, $field->field) ?? null;

        return [
            'type' => 'select',
            'name' => $field->field,
            'label' => $field->label ?? str($field->field)->title()->toString(),
            'value' => $direction,
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
            ],
        ];
    }
}
