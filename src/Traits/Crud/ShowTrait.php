<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ShowTrait
{
    protected function showQuery(Builder $query): Builder
    {
        return $query;
    }

    public function show(Request $request, $id): JsonResponse
    {

        $query = $this->model::query();
        $query = $this->globalQuery($query);
        $query = $this->showQuery($query);

        $item = $query->findOrFail($id);
        $this->can($this->getShowAbility(), $item);

        $this->beforeShowHook($request, $item);
        $data = $this->formatShowResponse($item);
        $data = array_merge($data, $this->extraShowData());

        $this->afterShowHook($request, $item);

        return $this->success(__('NaiveCrud::messages.success'), $data);
    }

    protected function extraShowData(): array
    {
        return [];
    }

    protected function formatShowResponse(Model $item): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        return $resource::makeCustom($item, $this->user)->resolve();
    }
}
