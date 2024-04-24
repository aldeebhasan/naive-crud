<?php

namespace Aldeebhasan\NaiveCrud\Lib;

use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**@method  static SortManager make(Request $request) */
class SortManager
{
    use Makable;

    public function __construct(protected Request $request)
    {
    }

    protected array $sorts;

    /**
     * @param SortUI|array<SortUI> $sorts
     * @return SortManager
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
        /*
         * $field = [
         *           'field'=>'param',                     // (required)
         *           'column'=>'column',                   // default: $field['field'] (optional)
         *           'direction'=>'asc|desc',               // default: desc
         *           'callback'=>'fn($query, $value)=>{}', // custom function (optional)
         *        ]
         */
        foreach ($fields as $field) {

            $param = $field['field'];
            $value = $field['value'] ?? $this->request->get($param);
            $column = $field['column'] ?? $param;
            $direction = $field['direction'] ?? 'desc';
            $callback = $field['callback'] ?? null;

            if (!empty($callback) && is_callable($callback)) {
                call_user_func($callback, $query, $value);
            } else {
                $query->orderBy($column, $direction);
            }
        }

    }

}