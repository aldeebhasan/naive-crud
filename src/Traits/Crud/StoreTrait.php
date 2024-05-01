<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait StoreTrait
{
    protected string $storeForm;

    public function store(Request $request): JsonResponse
    {

        /** @var FormRequest $form */
        $form = app($this->storeForm);
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
        $validated = $request->validate([
            'resources' => 'required|array|min:1',
        ]);

        $this->beforeBulkStoreHook($request);
        foreach ($validated ['resources'] as $data) {
            \request()->merge($data);
            /** @var FormRequest $form */
            $form = app($this->storeForm);
            $data = $form->validated();
            $data = array_merge($data, $this->extraStoreData());

            $item = new $this->model($data);
            $item->save();
        }

        $this->afterBulkStoreHook($request);
        $count = count($validated ['resources']);
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
