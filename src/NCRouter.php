<?php

namespace Aldeebhasan\NaiveCrud;

use Aldeebhasan\NaiveCrud\Http\Controllers\UploadController;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Router as BaseRouter;

class NCRouter extends BaseRouter
{
    public function apiResource($name, $controller, array $options = []): PendingResourceRegistration
    {
        $this->get("{$name}/search", [$controller, 'search'])->name("{$name}.search");
        $this->get("{$name}/export", [$controller, 'export'])->name("{$name}.export");
        $this->get("{$name}/direct-export", [$controller, 'exportDirect'])->name("{$name}.export-direct");
        $this->post("{$name}/import", [$controller, 'import'])->name("{$name}.import");
        $this->get("{$name}/import-template", [$controller, 'importTemplate'])->name("{$name}.import-template");

        return parent::apiResource($name, $controller, $options);
    }

    public function files($name): void
    {
        $this->post("{$name}/upload-image", [UploadController::class, 'image'])->name("{$name}.image");
        $this->post("{$name}/upload-file", [UploadController::class, 'file'])->name("{$name}.file");
    }
}
