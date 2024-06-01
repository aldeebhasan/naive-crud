<?php

namespace Aldeebhasan\NaiveCrud\Test\Traits;

use Illuminate\Support\Facades\Gate;

trait DeleteTestTrait
{
    public function test_destroy()
    {
        $this->registerDestroyGate();

        $item = $this->prepareItemToDelete();
        $params = array_merge($this->generalRouteParameters(), [$item->id]);
        $route = route($this->getResourcePrefix().'destroy', $params);
        $response = $this->delete($route);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->deleteResponseStructure());
        $this->assertSuccessfulDelete($item);

        $this->destroyAdditionalAssertion();
    }

    public function test_destroy_notfound()
    {
        $this->registerDestroyGate();

        $params = array_merge([-1], $this->generalRouteParameters());
        $route = route($this->getResourcePrefix().'destroy', $params);
        $response = $this->delete($route);
        $response->assertStatus(404);
    }

    protected function deleteResponseStructure(): array
    {
        return [
            'message',
            'message',
        ];
    }

    protected function destroyStateParameters(): array
    {
        return [];
    }

    protected function prepareItemToDelete()
    {
        $states = array_merge($this->generalStateParameters(), $this->destroyStateParameters());

        return $this->customFactory()->state($states)->create();
    }

    private function assertSuccessfulDelete($item): void
    {
        $model = app($this->getModel());
        $tableName = $model->getTable();
        if ($this->isSoftDeletableModel($model)) {
            $this->assertSoftDeleted($tableName, ['id' => $item->id]);
        } else {
            $this->assertDatabaseMissing($tableName, ['id' => $item->id]);
        }
    }

    protected function destroyAdditionalAssertion(): void
    {
    }

    private function registerDestroyGate(): void
    {
        $resource = $this->getResource();
        Gate::define("delete_$resource", fn () => true);
    }
}
