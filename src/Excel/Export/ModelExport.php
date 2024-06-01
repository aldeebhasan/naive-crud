<?php

namespace Aldeebhasan\NaiveCrud\Excel\Export;

use Aldeebhasan\NaiveCrud\Contracts\ExcelUI;
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
        if ($row instanceof ExcelUI) {
            return $row->formatExportItem();
        }

        return $row->toArray();
    }

    public function headings(): array
    {
        if (! empty(class_implements($this->model)[ExcelUI::class])){
            return $this->model::headerFields();
        }

        return [];
    }
}
