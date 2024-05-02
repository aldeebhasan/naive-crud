<?php

namespace Aldeebhasan\NaiveCrud\Lib;

use Aldeebhasan\NaiveCrud\Http\Controllers\UploadController;
use Aldeebhasan\NaiveCrud\Http\Routing\NCResourceRegistrar;
use Aldeebhasan\NaiveCrud\Http\Routing\PendingResourceRegistration;
use Aldeebhasan\NaiveCrud\Traits\Makable;

class RouteManager
{
    use Makable;

    public function ncResource(string $name, string $controller, array $options = []): PendingResourceRegistration
    {
        if (app()->bound(NCResourceRegistrar::class)) {
            $registrar = app()->make(NCResourceRegistrar::class);
        } else {
            $registrar = new NCResourceRegistrar(app('router'));
        }

        return new PendingResourceRegistration(
            $registrar,
            $name,
            $controller,
            $options
        );
    }

    public function files(string $name): void
    {
        $router = app('router');
        $router->post("{$name}/upload-image", [UploadController::class, 'image'])->name("{$name}.image");
        $router->post("{$name}/upload-file", [UploadController::class, 'file'])->name("{$name}.file");
    }
}
