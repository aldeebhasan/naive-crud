<?php

namespace Aldeebhasan\NaiveCrud\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface ExcelUI
{
    public static function headerFields(): array;

    public function formatExportItem(): array;

    public static function formatImportItem(array $row, Authenticatable $user): array;
}
