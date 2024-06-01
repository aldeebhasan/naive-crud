<?php

namespace Aldeebhasan\NaiveCrud\Test\Traits;

use Illuminate\Support\Facades\Gate;

trait ShowTestTrait
{
    public function test_show()
    {
        $this->registerShowGate();

        $item = $this->prepareItemToShow();
        $params = array_merge([$item->id], $this->generalRouteParameters());
        $route = route($this->getResourcePrefix().'show', $params);

        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->showResponseStructure());

    }

    public function test_show_fail()
    {
        $this->registerShowGate();

        $params = array_merge([-1], $this->generalRouteParameters());
        $route = route($this->getResourcePrefix().'show', $params);
        $response = $this->get($route);
        $response->assertStatus(404);
    }

    protected function showResponseStructure(): array
    {
        return [
            'message',
            'data' => [],
        ];
    }

    protected function showStateParameters(): array
    {
        return [];
    }

    protected function prepareItemToShow()
    {
        $states = array_merge($this->generalStateParameters(), $this->showStateParameters());

        return $this->customFactory()->state($states)->create();
    }

    private function registerShowGate(): void
    {
        $resource = $this->getResource();
        Gate::define("show_$resource", fn () => true);
    }
}
