<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseForm;
use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait UpdateTrait
{
    public function update(Request $request, $id): JsonResponse
    {

        /** @var FormRequest $form */
        $form = app($this->modelForm);
        $data = $form->validated();
        $data = array_merge($data, $this->extraUpdateData());

        $query = $this->model::query();
        $query = $this->globalQuery($query);

        $item = $query->findOrFail($id);
        $this->can($this->getUpdateAbility(), $item);

        $this->beforeUpdateHook($request, $item);
        $item->update($data);
        $this->afterUpdateHook($request, $item);

        $data = $this->formatUpdateResponse($item);

        return $this->success(__('NaiveCrud::messages.updated'), $data, 201);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $this->can($this->getUpdateAbility());
        /** @var BaseForm $form */
        $form = app($this->modelForm);
        $data = $form->validated();

        $query = $this->model::query();
        $query = $this->globalQuery($query);

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
