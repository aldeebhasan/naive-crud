<?php

namespace Aldeebhasan\NaiveCrud\Logic\Resolvers;

use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\DTO\SortField;
use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use function Orchestra\Testbench\default_skeleton_path;

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
            $fields = $sort->fields();
            $this->handleFields($query, $fields);
        }

        return $query;
    }

    private function handleFields(Builder $query, array $fields): void
    {
        /** @var SortField $field */
        foreach ($fields as $field) {

            if (!in_array($field->field, $this->values)) continue;

            $direction = Arr::get($this->values, $field->field) ?? $field->defaultDirection;
            $direction = in_array($direction, ['asc', 'desc']) ? $direction : $field->defaultDirection;

            if (!empty($field->callback) && is_callable($field->callback)) {
                call_user_func($field->callback, $query);
            } else {
                $query->orderBy($field->column, $direction);
            }
        }

    }
}
