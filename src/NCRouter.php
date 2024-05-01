<?php

namespace Aldeebhasan\NaiveCrud;

use Aldeebhasan\NaiveCrud\Http\Controllers\UploadController;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Router as BaseRouter;

class NCRouter extends BaseRouter
{
    private function registerPackageRoutes(string $name, string $controller, bool $withBulk): void
    {
        $this->get("{$name}/search", [$controller, 'search'])->name("{$name}.search");
        $this->get("{$name}/export", [$controller, 'export'])->name("{$name}.export");
        $this->get("{$name}/direct-export", [$controller, 'exportDirect'])->name("{$name}.export-direct");
        $this->post("{$name}/import", [$controller, 'import'])->name("{$name}.import");
        $this->get("{$name}/import-template", [$controller, 'importTemplate'])->name("{$name}.import-template");

        if ($withBulk) {
            $this->post("{$name}/bulk", [$controller, 'bulkStore'])->name("{$name}.bulk-store");
            $this->put("{$name}/bulk", [$controller, 'bulkUpdate'])->name("{$name}.bulk-update");
            $this->delete("{$name}/bulk", [$controller, 'bulkDelete'])->name("{$name}.bulk-delete");
        }
    }

    public function apiResource($name, $controller, array $options = [], bool $withBulk = false): PendingResourceRegistration
    {
        $this->registerPackageRoutes($name, $controller, $withBulk);

        return parent::apiResource($name, $controller, $options);
    }

    public function resource($name, $controller, array $options = [], bool $withBulk = false): PendingResourceRegistration
    {
        $this->registerPackageRoutes($name, $controller, $withBulk);

        return parent::resource($name, $controller, $options);
    }

    public function files($name): void
    {
        $this->post("{$name}/upload-image", [UploadController::class, 'image'])->name("{$name}.image");
        $this->post("{$name}/upload-file", [UploadController::class, 'file'])->name("{$name}.file");
    }
}
