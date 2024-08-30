<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait SearchTrait
{
    protected string $searchKeyword = 'q';

    protected function searchQuery(Builder $query, string $value = ''): Builder
    {
        return $query;
    }

    public function search(Request $request): Response|Responsable
    {
        $this->can($this->getIndexAbility());

        $value = $request->get($this->searchKeyword, '') ?: '';
        $this->beforeSearchHook($request);

        $query = $this->fullQueryResolver($request)
            ->setExtendQuery($this->searchQuery(...), $value)
            ->build();

        $items = $query->paginate($this->getLimit());

        $data = $this->formatSearchItems($items);
        $this->afterSearchHook($request);

        return $this->searchResponse(__('NaiveCrud::messages.success'), $data);
    }

    protected function searchResponse(string $message, array $data): Response|Responsable
    {
        return $this->success($data, $message);
    }

    protected function formatSearchItems(Paginator $items): array
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
