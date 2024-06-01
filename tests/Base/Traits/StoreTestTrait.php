<?php

namespace Aldeebhasan\NaiveCrud\Test\Base\Traits;

use Illuminate\Support\Facades\Gate;

trait StoreTestTrait
{
    public function test_store(): void
    {
        $this->registerStoreGate();

        $item = $this->prepareItemToStore();
        $params = $this->generalRouteParameters();
        $route = route($this->getResourcePrefix().'store', $params);

        $formatted = $this->formatStoreData($item);
        $response = $this->post($route, $formatted);
        $response->assertCreated();
        $response->assertJsonStructure($this->storeResponseStructure());
        $this->storeAdditionalAssertion();
    }

    public function test_store_fail(): void
    {
        $this->registerStoreGate();

        $params = $this->generalRouteParameters();
        $route = route($this->getResourcePrefix().'store', $params);

        $response = $this->post($route, []);
        $response->assertStatus(422);
    }

    protected function storeResponseStructure(): array
    {
        return [
            'message',
            'data',
        ];
    }

    protected function storeStateParameters(): array
    {
        return [];
    }

    protected function formatStoreData($item): array
    {
        return $item->toArray();
    }

    protected function prepareItemToStore()
    {
        $states = array_merge($this->generalStateParameters(), $this->storeStateParameters());

        return $this->customFactory()->state($states)->make();
    }

    protected function storeAdditionalAssertion(): void
    {
    }

    private function registerStoreGate(): void
    {
        $resource = $this->getResource();
        Gate::define("create_$resource", fn () => true);
    }
}
