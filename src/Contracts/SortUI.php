<?php

namespace Aldeebhasan\NaiveCrud\Contracts;

use Aldeebhasan\NaiveCrud\DTO\SortField;

interface SortUI
{
    /**
     * Get the list of fields for this class.
     *
     * @return array<SortField>
     */
    public function fields(): array;
}
