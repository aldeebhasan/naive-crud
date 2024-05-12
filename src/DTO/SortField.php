<?php

namespace Aldeebhasan\NaiveCrud\DTO;

final class SortField
{
    public readonly string $field;

    public readonly ?string $column;

    public readonly string $defaultDirection;

    public readonly mixed $callback;

    public function __construct(
        string   $field,
        ?string  $column = null,
        ?string  $defaultDirection = 'desc',
        callable $callback = null
    )
    {
        $this->field = $field;
        $this->column = $column ?? $field;
        $this->defaultDirection = $defaultDirection;
        $this->callback = $callback;
    }
}
