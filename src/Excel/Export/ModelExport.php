<?php

namespace Aldeebhasan\NaiveCrud\Excel\Export;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ModelExport implements WithMapping, WithHeadings
{
    use Exportable;

    private string $model;

    public function forModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function map($row): array
    {
        if (method_exists($row, 'formatExportItem')) {
            return $row->formatExportItem();
        }

        return $row->toArray();
    }

    public function headings(): array
    {
        if (method_exists($this->model, 'importFields')) {
            return $this->model::importFields();
        }

        return [];
    }
}
