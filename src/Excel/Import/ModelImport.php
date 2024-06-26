<?php

namespace Aldeebhasan\NaiveCrud\Excel\Import;

use Aldeebhasan\NaiveCrud\Contracts\ExcelUI;
use Illuminate\Contracts\Auth\Authenticatable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

readonly class ModelImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow
{
    public function __construct(private string $model, private Authenticatable $user)
    {
    }

    public function model(array $row)
    {
        if (! empty(class_implements($this->model)[ExcelUI::class])){
            $row = $this->model::formatImportItem($row, $this->user);

            return new $this->model($row);
        }

        return null;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
