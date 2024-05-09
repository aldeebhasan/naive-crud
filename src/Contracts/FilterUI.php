<?php

namespace Aldeebhasan\NaiveCrud\Contracts;

use Aldeebhasan\NaiveCrud\DTO\FilterField;

interface FilterUI
{
    /**
     * Get the list of fields for this class.
     *
     * @return array<FilterField>
     */
    public function fields(): array;
}
