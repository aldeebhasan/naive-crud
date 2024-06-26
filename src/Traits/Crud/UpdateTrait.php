<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

trait UpdateTrait
{
    protected ?string $updateAction = null;

    protected ?string $bulkUpdateAction = null;

    /** @param BaseRequest $request */
    public function update(Request $request, $id): Response|Responsable
    {
        $query = $this->baseQueryResolver($request)->build();

        $item = $query->findOrFail($id);
        $this->can($this->getUpdateAbility(), $item);

        $data = $request->validated();
        $data = array_merge($data, $this->extraUpdateData());

        $this->beforeUpdateHook($request, $item);

        $action = $this->resolveUpdateAction();
        App::make($action)->setModel($item)->setData($data)->handle();

        $this->afterUpdateHook($request, $item);

        $data = $this->formatUpdateItem($item);

        return $this->updateResponse(__('NaiveCrud::messages.updated'), $data);
    }

    protected function updateResponse(string $message, array $data): Response|Responsable
    {
        return $this->success($data, $message);
    }

    /** @param BaseRequest $request */
    public function bulkUpdate(Request $request): Response|Responsable
    {
        $this->can($this->getUpdateAbility());

        $data = $request->validated();

        $query = $this->baseQueryResolver($request)->build();

        $this->beforeBulkUpdateHook($request);

        $action = $this->resolveBulkUpdateAction();
        $count = 0;
        $ids = array_keys($data['resources']);
        $items = $query->whereKey($ids)->get();
        $key = $this->getModelKey();
        foreach ($data['resources'] as $id => $itemData) {
            $item = $items->firstWhere($key, $id);
            if (! $item) continue;

            $itemData = array_merge($itemData, $this->extraUpdateData());
            App::make($action)->setModel($item)->setData($itemData)->handle();
            $count++;
        }

        $this->afterBulkUpdateHook($request);

        return $this->bulkUpdateResponse(__('NaiveCrud::messages.bulk-updated', ['count' => $count]));
    }

    protected function bulkUpdateResponse(string $message): Response|Responsable
    {
        return $this->success(message: $message);
    }

    protected function extraUpdateData(): array
    {
        return [];
    }

    protected function formatUpdateItem(Model $item): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        return $resource::makeCustom($item, $this->resolveUser(), false)->resolve();
    }

    private function resolveUpdateAction(): string
    {
        return $this->componentsResolver->resolveModelAction('update', $this->updateAction);
    }

    private function resolveBulkUpdateAction(): string
    {
        return $this->componentsResolver->resolveModelAction('bulkUpdate', $this->bulkUpdateAction ?? $this->updateAction);
    }
}
