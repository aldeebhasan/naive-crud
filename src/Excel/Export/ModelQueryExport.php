<?php

namespace Aldeebhasan\NaiveCrud\Excel\Export;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;

class ModelQueryExport extends ModelExport implements FromQuery
{
    public function __construct(private readonly Builder $builder)
    {
    }

    public function query(): Builder
    {
        return $this->builder;
    }
}
