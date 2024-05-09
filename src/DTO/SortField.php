<?php

namespace Aldeebhasan\NaiveCrud\DTO;

final class SortField
{
    public readonly string $field;
    public readonly ?string $column;
    public readonly string $direction;
    public readonly mixed $callback;

    public function __construct(
        string   $field,
        ?string  $column = null,
        ?string  $direction = 'desc',
        callable $callback = null
    )
    {
        $this->field = $field;
        $this->column = $column ?? $field;
        $this->direction = $direction;
        $this->callback = $callback;
    }
}
