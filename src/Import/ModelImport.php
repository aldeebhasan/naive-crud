<?php

namespace Aldeebhasan\NaiveCrud\Import;

use Illuminate\Contracts\Auth\Authenticatable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ModelImport implements ToModel, WithChunkReading, WithBatchInserts
{
    public function __construct(private readonly string $model, private readonly Authenticatable $user)
    {
    }

    public function model(array $row)
    {
        if (method_exists($this->model, 'formatImportItem')) {
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
