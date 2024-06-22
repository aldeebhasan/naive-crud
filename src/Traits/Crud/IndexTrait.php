<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\FilterResolver;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\SortResolver;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

trait IndexTrait
{
    protected bool $paginated = true;

    protected function indexQuery(Builder $query): Builder
    {
        return $query;
    }

    public function index(Request $request): Response
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

    public function fields(Request $request): Response
    {
        $this->can($this->getIndexAbility());

        $data['filters'] = FilterResolver::make($request)->setFilters($this->getFilters())->render();
        $data['sorters'] = SortResolver::make($request)->setSorters($this->getSorters())->render();

        return $this->success($data, __('NaiveCrud::messages.success'));
    }

    protected function getLimit(): ?int
    {
        return request('limit', null);
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
