<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\FilterResolver;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\SortResolver;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

trait IndexTrait
{
    protected bool $paginated = true;
    protected bool $preserveQueryParams = true;
    protected array $paginationMeta = ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'];

    protected function indexQuery(Builder $query): Builder
    {
        return $query;
    }

    public function index(Request $request): Response|Responsable
    {
        $this->can($this->getIndexAbility());

        $this->beforeIndexHook($request);

        $query = $this->fullQueryResolver($request)
            ->setExtendQuery($this->indexQuery(...))
            ->build();

        if ($this->paginated) {
            $items = $query->paginate(perPage: $this->getLimit());
            if ($this->preserveQueryParams) {
                $items->withQueryString();
            }
        } else {
            $items = $query->get();
        }
        $data = $this->formatIndexItems($items);
        $data = array_merge($data, $this->extraIndexData());
        $this->afterIndexHook($request);

        return $this->indexResponse(__('NaiveCrud::messages.success'), $data);
    }

    protected function indexResponse(string $message, array $data): Response|Responsable
    {
        return $this->success($data, $message);
    }

    public function fields(Request $request): Response|Responsable
    {
        $this->can($this->getIndexAbility());

        $data = $this->getFilterAndSortFields($request);

        return $this->success($data, __('NaiveCrud::messages.success'));
    }

    protected function getFilterAndSortFields(Request $request): array
    {
        return [
            'filters' => FilterResolver::make($request)->setFilters($this->getFilters())->render(),
            'sorters' => SortResolver::make($request)->setSorters($this->getSorters())->render(),
        ];
    }

    protected function getLimit(): ?int
    {
        return request('limit', null);
    }

    protected function extraIndexData(): array
    {
        return [];
    }

    protected function formatIndexItems(Collection|Paginator $items): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        if ($this->paginated) {
            return [
                'items' => $resource::collectionCustom($items->items(), $this->resolveUser())->toArray(),
                'meta' => Arr::only($items->toArray(), $this->paginationMeta),
            ];
        } else {
            return $resource::collectionCustom($items)->toArray();
        }
    }
}
