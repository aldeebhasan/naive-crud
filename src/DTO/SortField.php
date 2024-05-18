<?php

namespace Aldeebhasan\NaiveCrud\DTO;

use Aldeebhasan\NaiveCrud\Traits\Makable;

/**@method  static SortField make(string $field, ?string $column = null, ?string $defaultDirection = 'desc', callable $callback = null) */
final readonly class SortField
{
    use Makable;

    public string $field;

    public ?string $column;

    public string $defaultDirection;

    public mixed $callback;

    public function __construct(
        string $field,
        ?string $column = null,
        ?string $defaultDirection = 'desc',
        callable $callback = null
    )
    {
        $this->field = $field;
        $this->column = $column ?? $field;
        $this->defaultDirection = $defaultDirection;
        $this->callback = $callback;
    }
}
