<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait SearchTrait
{
    protected string $searchKeyword = 'q';

    protected function searchQuery(Builder $query, string $value = ''): Builder
    {
        return $query;
    }

    public function search(Request $request): JsonResponse
    {
        $this->can($this->getIndexAbility());

        $value = $request->get($this->searchKeyword, '');
        $this->beforeSearchHook($request);

        $query = $this->fullQueryResolver($request)
            ->setExtendQuery($this->searchQuery(...), $value)
            ->build();

        $items = $query->paginate($this->getLimit());

        $data = $this->formatSearchResponse($items);
        $this->afterSearchHook($request);

        return $this->success($data, __('NaiveCrud::messages.success'));
    }

    protected function formatSearchResponse(Paginator $items): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        return [
            'items' => $resource::collectionCustom($items->items(), $this->resolveUser(), forSearch: true)->toArray(),
            'meta' => [
                'has_more_page' => $items->hasMorePages(),
            ],
        ];
    }
}
