<?php

namespace Aldeebhasan\NaiveCrud\Export;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class ModelExport implements WithMapping
{
    use Exportable;

    public function map($row): array
    {
        if (method_exists($row, 'formatExportItem')) {
            return $row->formatExportItem();
        }

        return $row->toArray();
    }
}
