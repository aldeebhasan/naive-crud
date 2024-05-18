<?php

namespace Aldeebhasan\NaiveCrud\Excel\Export;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ModelCollectionExport extends ModelExport implements FromCollection
{
    public function __construct(private readonly Collection $collection)
    {
    }

    public function collection()
    {
        return $this->collection;
    }
}
