<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait ShowTrait
{
    protected function showQuery(Builder $query): Builder
    {
        return $query;
    }

    public function show(Request $request, $id): Response|Responsable
    {

        $query = $this->baseQueryResolver($request)
            ->setExtendQuery($this->showQuery(...))
            ->build();

        $item = $query->findOrFail($id);
        $this->can($this->getShowAbility(), $item);

        $this->beforeShowHook($request, $item);
        $data = $this->formatShowItem($item);
        $data = array_merge($data, $this->extraShowData());

        $this->afterShowHook($request, $item);

        return $this->showResponse(__('NaiveCrud::messages.success'), $data);
    }

    protected function showResponse(string $message, array $data): Response|Responsable
    {
        return $this->success($data, $message);
    }

    protected function extraShowData(): array
    {
        return [];
    }

    protected function formatShowItem(Model $item): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        return $resource::makeCustom($item, $this->resolveUser())->resolve();
    }
}
