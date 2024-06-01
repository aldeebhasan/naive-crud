<?php

namespace Aldeebhasan\NaiveCrud\Test\Traits;

use Aldeebhasan\NaiveCrud\Logic\Managers\FileManager;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

/**@property   bool $exportAllShouldQueue */
trait ExportTestTrait
{
    public function test_export_single_page()
    {
        $this->registerExportGate();
        $this->prepareItemToExport();

        $params = $this->generalRouteParameters();
        $route = route($this->getResourcePrefix().'export', $params);

        Excel::fake();
        $response = $this->get($route);
        $response->assertOk();
        Excel::assertDownloaded($this->getFileName().'_page.csv');

    }

    public function test_export_all_page()
    {
        $this->registerExportGate();
        $this->prepareItemToExport();

        $params = array_merge(['target' => 'all'], $this->generalRouteParameters());
        $route = route($this->getResourcePrefix().'export', $params);

        Excel::fake();
        $response = $this->get($route);
        $response->assertOk();
        $fileName = $this->getFileName().'_all.csv';
        if (! empty($this->exportAllShouldQueue)) {
            $storagePath = FileManager::make()->getStoragePath("exports/{$fileName}");
            Excel::assertQueued($storagePath);
        } else {
            Excel::assertDownloaded($fileName);
        }

    }

    private function getFileName(): string
    {
        $fileName = 'data';
        if ($resource = $this->getResource()) {
            $namesArray = explode('.', $resource);
            $fileName = end($namesArray);
        }

        return $fileName;
    }

    protected function exportStateParameters(): array
    {
        return [];
    }

    protected function prepareItemToExport(): void
    {
        $states = array_merge($this->generalStateParameters(), $this->exportStateParameters());
        $this->customFactory()->state($states)->count(3)->create();
    }

    private function registerExportGate(): void
    {
        $resource = $this->getResource();
        Gate::define("export_$resource", fn () => true);
    }
}
