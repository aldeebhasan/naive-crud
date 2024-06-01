<?php

namespace Aldeebhasan\NaiveCrud\Test\Base\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

trait ImportTestTrait
{
    public function test_import()
    {
        $this->registerImportGate();
        $file = 'public/tmp/models.csv';

        $count = $this->getModel()::query()->count();
        $items = $this->prepareItemToImport();
        $items->transform(fn ($item) => $this->formatImportData($item));
        $items->prepend(array_keys($items->first()));

        $items->storeExcel($file);

        $route = route($this->getResourcePrefix().'import', $this->generalRouteParameters());
        $response = $this->post($route, [
            'file' => storage_path('app/'.$file),
        ]);
        $response->assertOk();
        $this->assertDatabaseCount($this->getModel(), $count + 3);
    }

    public function test_export_template()
    {
        $this->registerImportGate();

        Excel::fake();
        $route = route($this->getResourcePrefix().'importTemplate', $this->generalRouteParameters());
        $response = $this->get($route);
        $response->assertOk();
        Excel::assertDownloaded($this->getResource().'-template.csv');
    }

    protected function formatImportData($item): array
    {
        return $item->toArray();
    }

    protected function importStateParameters(): array
    {
        return [];
    }

    protected function prepareItemToImport(): Collection
    {
        $states = array_merge($this->generalStateParameters(), $this->importStateParameters());

        return $this->customFactory()->state($states)->count(3)->make();
    }

    private function registerImportGate(): void
    {
        $resource = $this->getResource();
        Gate::define("import_$resource", fn () => true);
    }
}
