<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Excel\Export\ModelCollectionExport;
use Aldeebhasan\NaiveCrud\Excel\Export\ModelQueryExport;
use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Aldeebhasan\NaiveCrud\Jobs\CompletedExportJob;
use Aldeebhasan\NaiveCrud\Logic\Managers\FileManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\Response;

trait ExportTrait
{
    protected ?string $completedJobNotification = null;

    protected bool $exportAllShouldQueue = true;

    protected function exportQuery(Builder $query): Builder
    {
        return $this->indexQuery($query);
    }

    /** @param BaseRequest $request */
    public function export(Request $request): Response|JsonResponse
    {
        $this->can($this->getExportAbility());

        $validated = $request->validated();

        $this->beforeExportHook($request);

        $query = $this->fullQueryResolver($request)
            ->setExtendQuery($this->exportQuery(...))
            ->build();

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

            return $this->success(message: __('NaiveCrud::messages.exported'));
        }

        return $handler->download($fileName);
    }
}
