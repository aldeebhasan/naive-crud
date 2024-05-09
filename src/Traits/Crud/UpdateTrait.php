<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait UpdateTrait
{
    /** @param BaseRequest $request */
    public function update(Request $request, $id): JsonResponse
    {
        $query = $this->model::query();
        $query = $this->baseQuery($query);

        $item = $query->findOrFail($id);
        $this->can($this->getUpdateAbility(), $item);

        $data = $request->validated();
        $data = array_merge($data, $this->extraUpdateData());

        $this->beforeUpdateHook($request, $item);
        $item->update($data);
        $this->afterUpdateHook($request, $item);

        $data = $this->formatUpdateResponse($item);

        return $this->success(__('NaiveCrud::messages.updated'), $data, 201);
    }

    /** @param BaseRequest $request */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $this->can($this->getUpdateAbility());

        $data = $request->validated();

        $query = $this->baseQueryResolver($request)->build();

        $this->beforeBulkUpdateHook($request);
        $count = 0;
        foreach ($data['resources'] as $id => $itemData) {
            $item = $query->find($id);
            if (! $item) continue;

            $itemData = array_merge($itemData, $this->extraUpdateData());
            $item->update($itemData);
            $count++;
        }
        $this->afterBulkUpdateHook($request);

        return $this->success(__('NaiveCrud::messages.bulk-updated', ['count' => $count]));
    }

    protected function extraUpdateData(): array
    {
        return [];
    }

    protected function formatUpdateResponse(Model $item): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        return $resource::makeCustom($item, $this->user, false)->resolve();
    }
}
