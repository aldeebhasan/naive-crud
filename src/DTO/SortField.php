<?php

namespace Aldeebhasan\NaiveCrud\DTO;

use Aldeebhasan\NaiveCrud\Traits\Makable;

/**@method  static SortField make(string $field, ?string $column = null, ?string $value = 'desc', callable $callback = null) */
final readonly class SortField
{
    use Makable;

    public ?string $column;

    public mixed $callback;

    public function __construct(
        public string $field,
        ?string $column = null,
        callable $callback = null,
        public ?string $label = null,
        public ?string $value = 'desc',
    )
    {
        $this->column = $column ?? $field;
        $this->callback = $callback;
    }
}
