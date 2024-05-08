<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait DeleteTrait
{
    public function destroy(Request $request, $id): JsonResponse
    {

        $query = $this->model::query();
        $query = $this->baseQuery($query);

        $item = $query->findOrFail($id);
        $this->can($this->getDeleteAbility(), $item);

        $this->beforeDeleteHook($request, $item);
        $item->delete();
        $this->afterDeleteHook($request, $item);

        return $this->success(__('NaiveCrud::messages.deleted'));
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        $this->can($this->getDeleteAbility());

        $validated = $request->validate([
            'resources' => 'required|array|min:1',
            'resources.*' => 'required|integer',
        ]);
        $query = $this->model::query();
        $query = $this->baseQuery($query);

        $this->beforeBulkDeleteHook($request);
        $ids = $validated['resources'];
        $count = $query->whereKey($ids)->delete();
        $this->afterBulkDeleteHook($request);

        return $this->success(__('NaiveCrud::messages.bulk-deleted', ['count' => $count]));
    }
}
