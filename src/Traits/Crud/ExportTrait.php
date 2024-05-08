<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Export\ModelCollectionExport;
use Aldeebhasan\NaiveCrud\Export\ModelQueryExport;
use Aldeebhasan\NaiveCrud\Jobs\CompletedExportJob;
use Aldeebhasan\NaiveCrud\Logic\Managers\FileManager;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\FilterResolver;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\SortResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;

trait ExportTrait
{
    protected ?string $completedJobNotification = null;

    protected bool $exportAllShouldQueue = true;

    protected function exportQuery(Builder $query): Builder
    {
        return $this->indexQuery($query);
    }

    private function prepareExportQuery(Request $request): Builder
    {
        $query = $this->model::query();
        $query = $this->baseQuery($query);
        $query = $this->exportQuery($query);

        FilterResolver::make($request)->setFilters($this->filters)->apply($query);
        SortResolver::make($request)->setSorters($this->sorters)->apply($query);

        return $query;
    }

    public function export(Request $request): Response|JsonResponse
    {
        $this->can($this->getExportAbility());

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

        $this->afterExportHook($request);

        if ($target === 'all' && $this->exportAllShouldQueue) {
            $storagePath = FileManager::make()->getStoragePath("exports/{$fileName}");
            $assetPath = FileManager::make()->getAssetPath("exports/{$fileName}");
            $handler->queue($storagePath)->chain([
                new CompletedExportJob(request()->user(), $assetPath, $this->completedJobNotification),
            ]);

            return $this->success(__('NaiveCrud::messages.exported'));
        }

        return $handler->download($fileName);
    }
}
