<?php

namespace Aldeebhasan\NaiveCrud\Traits;

use Aldeebhasan\NaiveCrud\Logic\Resolvers\QueryResolver;
use Illuminate\Http\Request;

trait QueryResolverTrait
{
    public function baseQueryResolver(Request $request): QueryResolver
    {
        return QueryResolver::make($this->model, $this->baseQuery(...))
            ->setRequest($request);
    }

    public function fullQueryResolver(Request $request): QueryResolver
    {
        return $this->baseQueryResolver($request)
            ->setFilters($this->getFilters())
            ->setSorters($this->getSorters());
    }
}
