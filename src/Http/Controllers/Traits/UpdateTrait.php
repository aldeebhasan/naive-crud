<?php

namespace Aldeebhasan\NaiveCrud\Http\Controllers\Traits;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait UpdateTrait
{
    protected string $updateForm;

    protected function beforeUpdateHook(Request $request, Model $model): void
    {
        // do some thing before call update function;
    }

    public function update(Request $request, $id): JsonResponse
    {

        /** @var FormRequest $form */
        $form = app($this->updateForm);
        $data = $form->validated();
        $data = array_merge($data, $this->extraUpdateData());

        $item = $this->model::findOrFail($id);
        $this->beforeUpdateHook($request, $item);
        $item->update($data);
        $this->afterUpdateHook($request, $item);

        $data = $this->formatUpdateResponse($item);

        return $this->success(__('NaiveCrud::messages.updated'), $data, 201);
    }

    protected function afterUpdateHook(Request $request, Model $model): void
    {
        // do some thing after call update function;
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
