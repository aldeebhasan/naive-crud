<?php

namespace Aldeebhasan\NaiveCrud\DTO;

/**@method  static FilterField make(string $field, ?string $column = null, ?string $operator = '=', callable $callback = null, mixed $value = null) */
final readonly class FilterField
{
    public ?string $column;

    public mixed $callback;

    public ?string $relation;

    public function __construct(
        public string $field,
        ?string $column = null,
        public ?string $operator = '=',
        callable $callback = null,
        public string $type = 'text',
        public ?string $label = null,
        public mixed $value = null
    )
    {
        $this->callback = $callback;
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
