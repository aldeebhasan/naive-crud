<?php

namespace Aldeebhasan\NaiveCrud\Test\Base\Traits;

use Illuminate\Support\Facades\Gate;

trait SearchTestTrait
{
    public function test_search()
    {
        $this->registerSearchGate();

        $this->prepareItemToSearch();
        $params = $this->generalRouteParameters();
        $route = route($this->getResourcePrefix().'search', $params);

        $response = $this->get($route, ['search' => '']);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->searchResponseStructure());
    }

    protected function searchResponseStructure(): array
    {
        return [
            'data' => [
                'items' => [
                    '*' => $this->searchItemStructure(),
                ],
                'meta' => [
                    'has_more_page',
                ],
            ],
        ];
    }

    protected function searchItemStructure(): array
    {
        return [];
    }

    protected function searchStateParameters(): array
    {
        return [];
    }

    protected function prepareItemToSearch()
    {
        $states = array_merge($this->generalStateParameters(), $this->searchStateParameters());
        $this->customFactory()->state($states)->count(3)->create();
    }

    private function registerSearchGate(): void
    {
        $resource = $this->getResource();
        Gate::define("index_$resource", fn () => true);
    }
}
