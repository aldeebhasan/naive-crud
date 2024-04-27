<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Import\ModelImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

trait ImportTrait
{
    public function import(Request $request): JsonResponse
    {

        $validated = $request->validate(['file' => 'required|string']);
        $file = $validated['file'];

        $user = auth()->user();
        Excel::import(new ModelImport($this->model, $user), $file);

        return $this->success(__('NaiveCrud::messages.imported'));
    }

    public function importTemplate(Request $request): JsonResponse
    {
        $name = Str::snake(Str::pluralStudly(class_basename($this->model)));
        if (method_exists($this->model, 'importFields')) {
            $sample = $this->model::importFields()->mapWithKeys(fn ($name) => [$name => $name])->toArray();

            return collect([$sample])->downloadExcel("{$name}-template.csv");
        }

        throw new \BadMethodCallException("Model $name doesnt has template fields", 400);
    }
}
