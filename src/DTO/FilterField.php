<?php

namespace Aldeebhasan\NaiveCrud\DTO;

final class FilterField
{
    public readonly string $field;

    public readonly ?string $column;

    public readonly ?string $operator;

    public readonly mixed  $callback;

    public readonly mixed $value;

    public readonly ?string $relation;

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
        }else{
            $this->relation = null;
            $this->column = $column ?? $field;
        }
    }
}
