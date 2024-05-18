<?php

namespace Aldeebhasan\NaiveCrud\Excel\Export;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateExport implements FromArray, WithHeadings
{
    public function __construct(protected array $fields)
    {
    }

    public function array(): array
    {
        return [
            array_fill(0, count($this->fields), ' '),
        ];
    }

    public function headings(): array
    {
        return array_keys($this->fields);
    }
}
