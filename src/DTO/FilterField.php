<?php

namespace Aldeebhasan\NaiveCrud\DTO;
final class FilterField
{
    public string $field;
    public ?string $column;
    public ?string $operator;
    public $callback = null;
    public mixed $value;
    public ?string $relation = null;

    /**
     * @param string $field
     * @param string|null $column
     * @param string|null $operator
     * @param $callback
     * @param mixed|null $value
     */
    public function __construct(string $field, ?string $column = null, ?string $operator = "=", $callback = null, mixed $value = null)
    {
        $this->field = $field;
        $this->column = $column ?? $field;
        $this->operator = $operator ?? "=";
        $this->callback = $callback;
        $this->value = $value;

        if (str_contains('.', $this->column)) {
            [$relation, $relationColumn] = explode('.', $column);
            $this->relation = $relation;
            $this->column = $relationColumn;
        }
    }


}