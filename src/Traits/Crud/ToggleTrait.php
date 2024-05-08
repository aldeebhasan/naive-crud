<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ToggleTrait
{
    public function toggle(Request $request): JsonResponse
    {
        $this->can($this->getUpdateAbility());
        /** @var BaseForm $form */
        $form = app($this->modelRequestForm);
        $data = $form->validated();

        $query = $this->model::query();
        $query = $this->baseQuery($query);

        $this->beforeToggleHook($request);

        $resources = $data['resources'] ?? [];
        unset($data['resources']);
        $count = $query->whereKey($resources)->update($data);

        $this->afterToggleHook($request);

        return $this->success(__('NaiveCrud::messages.bulk-updated', ['count' => $count]));
    }
}
