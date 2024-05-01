<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait UpdateTrait
{
    protected string $updateForm;

    public function update(Request $request, $id): JsonResponse
    {

        /** @var FormRequest $form */
        $form = app($this->updateForm);
        $data = $form->validated();
        $data = array_merge($data, $this->extraUpdateData());

        $query = $this->model::query();
        $query = $this->globalQuery($query);

        $item = $query->findOrFail($id);

        $this->beforeUpdateHook($request, $item);
        $item->update($data);
        $this->afterUpdateHook($request, $item);

        $data = $this->formatUpdateResponse($item);

        return $this->success(__('NaiveCrud::messages.updated'), $data, 201);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'resources' => 'required|array|min:1',
        ]);
        $query = $this->model::query();
        $query = $this->globalQuery($query);

        $this->beforeBulkUpdateHook($request);
        $count = 0;
        foreach ($validated ['resources'] as $id => $data) {
            $item = $query->find($id);
            if (!$item) continue;

            \request()->merge($data);
            /** @var FormRequest $form */
            $form = app($this->updateForm);
            $data = $form->validated();
            $data = array_merge($data, $this->extraUpdateData());
            $item->update($data);
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
