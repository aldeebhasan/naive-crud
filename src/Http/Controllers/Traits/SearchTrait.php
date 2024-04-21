<?php

namespace Aldeebhasan\NaiveCrud\Http\Controllers\Traits;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait SearchTrait
{
    protected bool $paginated = true;

    protected function searchQuery(Builder $query): Builder
    {
        return $query;
    }

    public function search(Request $request): JsonResponse
    {
        $query = $this->model::query();
        $query = $this->globalQuery($query);
        $query = $this->searchQuery($query);

        $items = $query->paginate($this->getLimit());

        $data = $this->formatSearchResponse($items);

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
