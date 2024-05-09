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

    public function __construct(protected Request $request)
    {
    }

    protected array $sorts;

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
            if (! empty($field->callback) && is_callable($field->callback)) {
                call_user_func($field->callback, $query);
            } else {
                $query->orderBy($field->column, $field->direction);
            }
        }

    }
}
