<?php

namespace Aldeebhasan\NaiveCrud\DTO;

/**@method  static FilterField make(string $field, ?string $column = null, ?string $operator = '=', callable $callback = null, mixed $value = null) */
final readonly class FilterField
{
    public string $field;

    public ?string $column;

    public ?string $operator;

    public mixed $callback;

    public mixed $value;

    public ?string $relation;

    public function __construct(
        string $field,
        ?string $column = null,
        ?string $operator = '=',
        callable $callback = null,
        mixed $value = null
    )
    {
        $this->field = $field;
        $this->operator = $operator ?? '=';
        $this->callback = $callback;
        $this->value = $value;
        $column = $column ?? $field;

        if (str_contains('.', $column)) {
            [$relation, $relationColumn] = explode('.', $column);
            $this->relation = $relation;
            $this->column = $relationColumn;
        } else {
            $this->relation = null;
            $this->column = $column ?? $field;
        }
    }
}
