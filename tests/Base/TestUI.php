<?php

namespace Aldeebhasan\NaiveCrud\Test\Base;

use Illuminate\Database\Eloquent\Factories\Factory;

interface TestUI
{
    public function getModel(): string;

    public function getResource(): string;

    public function getResourcePrefix(): string;

    public function generalStateParameters(): array;

    public function generalRouteParameters(): array;

    public function customFactory(): Factory;
}
