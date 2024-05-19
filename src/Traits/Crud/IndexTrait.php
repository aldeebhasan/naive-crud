<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait IndexTrait
{
    protected bool $paginated = true;

    protected function indexQuery(Builder $query): Builder
    {
        return $query;
    }

    public function index(Request $request): JsonResponse
    {
        $this->can($this->getIndexAbility());

        $this->beforeIndexHook($request);

        $query = $this->fullQueryResolver($request)
            ->setExtendQuery($this->indexQuery(...))
            ->build();

        if ($this->paginated) {
            $items = $query->paginate(perPage: $this->getLimit());
        } else {
            $items = $query->get();
        }
        $data = $this->formatIndexResponse($items);
        $data = array_merge($data, $this->extraIndexData());
        $this->afterIndexHook($request);

        return $this->success($data, __('NaiveCrud::messages.success'));
    }

    protected function getLimit(): ?int
    {
        return request('limit', null);
    }

    protected function applyFilter(Builder $query): void
    {
        if (!empty($this->filter)) {
            $fields = $this->filter->fields();

        }
    }

    protected function extraIndexData(): array
    {
        return [];
    }

    protected function formatIndexResponse(Collection|Paginator $items): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        if ($this->paginated) {
            return [
                'items' => $resource::collectionCustom($items->items(), $this->resolveUser())->toArray(),
                'meta' => Arr::except($items->toArray(), [
                    'data', 'first_page_url', 'last_page_url', 'prev_page_url', 'next_page_url', 'links',
                ]),
            ];
        } else {
            return $resource::collectionCustom($items)->toArray();
        }
    }
}
