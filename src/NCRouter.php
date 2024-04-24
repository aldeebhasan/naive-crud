<?php

namespace Aldeebhasan\NaiveCrud;

use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Router as BaseRouter;
use Illuminate\Support\Facades\Route;

class NCRouter extends BaseRouter
{
    public function NCResource($name, $controller, array $options = []): PendingResourceRegistration
    {
        $this->get("{$name}/search", [$controller, "search"])->name("{$name}.search");
        $this->get("{$name}/export", [$controller, "export"])->name("{$name}.excel");
        $this->post("{$name}/import", [$controller, "import"])->name("{$name}.import");
        $this->get("{$name}/import-sample", [$controller, "importSample"])->name("{$name}.import-sample");

        return $this->apiResource($name, $controller, $options);
    }

    public function NCFiles($name, $controller, array $options = []): void
    {
        $this->post("{$name}/upload-image", [$controller, "image"])->name("{$name}.image");
        $this->post("{$name}/upload-file", [$controller, "file"])->name("{$name}.file");
    }

}


