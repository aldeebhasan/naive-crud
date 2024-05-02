<?php

namespace Aldeebhasan\NaiveCrud\Facades;

use Aldeebhasan\NaiveCrud\Http\Routing\PendingResourceRegistration;

/**
 * @method static PendingResourceRegistration ncResource(string $name, string $controller, array $options = [])
 * @method static void files(string $name)
 */
class NCRoute
{
    protected static function getFacadeAccessor(): string
    {
        return 'RouteManager';
    }
}
