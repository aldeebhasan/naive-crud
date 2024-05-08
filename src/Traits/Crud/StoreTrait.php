<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseForm;
use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait StoreTrait
{
    public function store(Request $request): JsonResponse
    {
        $this->can($this->getCreateAbility());
        /** @var BaseForm $form */
        $form = app($this->modelRequestForm);
        $data = $form->validated();
        $data = array_merge($data, $this->extraStoreData());

        $item = new $this->model($data);

        $this->beforeStoreHook($request, $item);
        $item->save();
        $this->afterStoreHook($request, $item);

        $data = $this->formatCreateResponse($item);

        return $this->success(__('NaiveCrud::messages.stored'), $data, 201);
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $this->can($this->getCreateAbility());

        /** @var BaseForm $form */
        $form = app($this->modelRequestForm);
        $data = $form->validated();

        $this->beforeBulkStoreHook($request);
        $count = 0;
        foreach ($data['resources'] as $itemData) {
            $itemData = array_merge($itemData, $this->extraStoreData());
            $item = new $this->model($itemData);
            $item->save();
            $count++;
        }

        $this->afterBulkStoreHook($request);

        return $this->success(__('NaiveCrud::messages.bulk-stored', ['count' => $count]));
    }

    protected function extraStoreData(): array
    {
        return [];
    }

    protected function formatCreateResponse(Model $item): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        return $resource::makeCustom($item, $this->user, false)->resolve();
    }
}
