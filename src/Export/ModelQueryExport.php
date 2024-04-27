<?php

namespace Aldeebhasan\NaiveCrud\Export;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;

class ModelQueryExport extends ModelExport implements FromQuery
{
    public function __construct(private readonly Builder $builder)
    {
    }

    public function query()
    {
        return $this->builder;
    }
}
