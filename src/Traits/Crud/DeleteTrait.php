<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait DeleteTrait
{
    protected function deleteQuery(Builder $query): Builder
    {
        return $query;
    }

    public function destroy(Request $request, $id): JsonResponse
    {

        $query = $this->model::query();
        $query = $this->globalQuery($query);
        $query = $this->deleteQuery($query);

        $item = $query->findOrFail($id);

        $this->beforeDeleteHook($request, $item);
        $item->delete();
        $this->afterDeleteHook($request, $item);

        return $this->success(__('NaiveCrud::messages.deleted'));
    }
}
