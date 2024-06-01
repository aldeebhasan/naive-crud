<?php

namespace Aldeebhasan\NaiveCrud\Test\Traits;

use Illuminate\Support\Facades\Gate;

trait IndexTestTrait
{
    public function test_index()
    {
        $this->registerIndexGate();

        $this->prepareItemToIndex();
        $route = route($this->getResourcePrefix().'index', $this->generalRouteParameters());
        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->indexResponseStructure());
    }

    protected function indexResponseStructure(): array
    {
        return [
            'data' => [
                'items' => [
                    '*' => $this->searchItemStructure(),
                ],
                'meta' => [
                    'current_page', 'from', 'last_page', 'per_page', 'total',
                ],
            ],
        ];
    }

    protected function indexItemStructure(): array
    {
        return [];
    }

    protected function indexStateParameters(): array
    {
        return [];
    }

    protected function prepareItemToIndex()
    {
        $states = array_merge($this->generalStateParameters(), $this->indexStateParameters());
        $this->customFactory()->state($states)->count(3)->create();
    }

    private function registerIndexGate(): void
    {
        $resource = $this->getResource();
        Gate::define("index_$resource", fn () => true);
    }
}
