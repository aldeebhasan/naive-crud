<?php

namespace Aldeebhasan\NaiveCrud\Test\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

trait UpdateTestTrait
{
    public function test_update()
    {
        $this->registerUpdateGate();
        $item = $this->prepareItemToUpdate();
        $params = array_merge([$item->id], $this->generalRouteParameters());
        $route = route($this->getResourcePrefix().'update', $params);

        $formatted = $this->formatUpdateData($item);
        $response = $this->put($route, $formatted);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->updateResponseStructure());

        $this->updateAdditionalAssertion();
    }

    public function test_update_fail()
    {
        $this->registerUpdateGate();

        $item = $this->prepareItemToUpdate();
        $params = array_merge([$item->id], $this->generalRouteParameters());
        $route = route($this->getResourcePrefix().'update', $params);
        $response = $this->put($route, []);
        $response->assertStatus(422);
    }

    protected function updateResponseStructure(): array
    {
        return [
            'message',
            'data',
        ];
    }

    protected function updateStateParameters(): array
    {
        return [];
    }

    protected function formatUpdateData($item): array
    {
        return $item->toArray();
    }

    protected function prepareItemToUpdate(): Model
    {
        $states = array_merge($this->generalStateParameters(), $this->updateStateParameters());

        return $this->customFactory()->state($states)->create();
    }

    protected function updateAdditionalAssertion(): void
    {
    }

    private function registerUpdateGate(): void
    {
        $resource = $this->getResource();
        Gate::define("update_$resource", fn () => true);
    }
}
