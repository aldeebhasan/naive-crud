<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ToggleTrait
{
    /** @param BaseRequest $request */
    public function toggle(Request $request): JsonResponse
    {
        $this->can($this->getUpdateAbility());

        $data = $request->validated();

        $query = $this->model::query();
        $query = $this->baseQuery($query);

        $this->beforeToggleHook($request);

        $ids = $data['resources'] ?? [];
        unset($data['resources']);
        $count = $query->whereKey($ids)->update($data);

        $this->afterToggleHook($request);

        return $this->success(__('NaiveCrud::messages.bulk-updated', ['count' => $count]));
    }
}
