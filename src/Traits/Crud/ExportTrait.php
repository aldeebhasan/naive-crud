<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Export\ModelCollectionExport;
use Aldeebhasan\NaiveCrud\Export\ModelQueryExport;
use Aldeebhasan\NaiveCrud\Jobs\CompletedExportJob;
use Aldeebhasan\NaiveCrud\Lib\FilterManager;
use Aldeebhasan\NaiveCrud\Lib\SortManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;

trait ExportTrait
{
    protected ?string $completedJobNotification = null;

    protected function exportQuery(Builder $query): Builder
    {
        return $this->indexQuery($query);
    }

    private function handleExport(Request $request): array
    {
        $validated = $request->validate([
            'type' => 'nullable|in:excel,csv,html',
            'target' => 'nullable|in:all,page',
        ]);

        $this->beforeExportHook($request);
        $query = $this->prepareExportQuery($request);
        $target = $validated['target'] ?? 'page';
        $targetType = match ($validated['type'] ?? 'csv') {
            'excel' => Excel::XLSX,
            'html' => Excel::HTML,
            default => Excel::CSV
        };
        $fileName = Str::snake(Str::pluralStudly(class_basename($this->model)));
        $fileName = sprintf('%s_%s.%s', $fileName, $target, strtolower($targetType));

        if ($target === 'page') {
            $items = $query->simplePaginate()->getCollection();
            $handler = new ModelCollectionExport($items);
        } else {
            $handler = new ModelQueryExport($query);
        }
        $handler->forModel($this->model);

        return [$handler, $fileName];
    }

    public function export(Request $request): JsonResponse
    {
        [$handler, $fileName] = $this->handleExport($request);
        $fileUrl = $this->getExportedFilePath($fileName);

        $handler->queue("/public/exports/$fileName")->chain([
            new CompletedExportJob(request()->user(), $fileUrl, $this->completedJobNotification),
        ]);
        $this->afterExportHook($request);

        return $this->success(__('NaiveCrud::messages.exported'));
    }

    public function exportDirect(Request $request): Response
    {
        [$handler, $fileName] = $this->handleExport($request);

        $this->afterExportHook($request);

        return $handler->download($fileName);
    }

    private function prepareExportQuery(Request $request): Builder
    {
        $query = $this->model::query();
        $query = $this->globalQuery($query);
        $query = $this->exportQuery($query);

        FilterManager::make($request)->setFilters($this->filters)->apply($query);
        SortManager::make($request)->setSorters($this->sorters)->apply($query);

        return $query;
    }

    private function getExportedFilePath(string $fileName): string
    {
        $base = '';
        if (config('filesystems.default') === 'local') {
            $base = 'storage/';
        } elseif (config('filesystems.default') === 's3') {
            $base = 'public/';
        }
        $path = "{$base}/exports/{$fileName}";

        return asset($path);
    }
}
