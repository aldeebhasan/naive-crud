<?php

namespace Aldeebhasan\NaiveCrud\Traits;

trait Makable
{
    public static function make(...$args): self
    {
        return new static(...$args);
    }
}
