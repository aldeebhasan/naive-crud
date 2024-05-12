<?php

namespace Aldeebhasan\NaiveCrud\Facades;

use Aldeebhasan\NaiveCrud\Http\Routing\PendingResourceRegistration;
use Illuminate\Support\Facades\Facade;

/**
 * @method static PendingResourceRegistration ncResource(string $name, string $controller, array $options = [])
 * @method static void files(string $name)
 */
class NCRoute extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'NCRoute';
    }
}
