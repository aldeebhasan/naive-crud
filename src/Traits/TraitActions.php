<?php

namespace Aldeebhasan\NaiveCrud\Traits;

use Illuminate\Validation\Rule;

trait TraitActions
{
    public static function toArray(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_column(self::cases(), 'name')
        );
    }

    public static function values(): array
    {
        return array_keys(self::toArray());
    }

    public static function rule(): array
    {
        return Rule::in(self::values());
    }
}
