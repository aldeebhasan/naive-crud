<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Aldeebhasan\NaiveCrud\Lib\FilterManager;
use Aldeebhasan\NaiveCrud\Lib\SortManager;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait SearchTrait
{
    protected string $searchKeyword = 'q';

    protected function searchQuery(Builder $query, string $value): Builder
    {
        return $query;
    }

    public function search(Request $request): JsonResponse
    {
        $value = $request->get($this->searchKeyword);
        $this->beforeSearchHook($request);
        $query = $this->model::query();
        $query = $this->globalQuery($query);
        $query = $this->searchQuery($query, $value);

        FilterManager::make($request)->setFilters($this->filters)->apply($query);
        SortManager::make($request)->setSorters($this->sorters)->apply($query);

        $items = $query->paginate($this->getLimit());

        $data = $this->formatSearchResponse($items);
        $this->afterSearchHook($request);

        return $this->success(__('NaiveCrud::messages.success'), $data);
    }

    protected function formatSearchResponse(Paginator $items): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        return [
            'items' => $resource::collectionCustom($items->items(), $this->user, forSearch: true)->toArray(),
            'meta' => [
                'has_more_page' => $items->hasMorePages(),
            ],
        ];
    }
}
