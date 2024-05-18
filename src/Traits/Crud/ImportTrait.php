<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Excel\Export\TemplateExport;
use Aldeebhasan\NaiveCrud\Excel\Import\ModelImport;
use Aldeebhasan\NaiveCrud\Exception\NCException;
use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

trait ImportTrait
{
    /** @param BaseRequest $request */
    public function import(Request $request): JsonResponse
    {
        $this->can($this->getImportAbility());

        $validated = $request->validated();
        $file = $validated['file'];

        $this->beforeImportHook($request);
        $user = auth()->user();
        Excel::import(new ModelImport($this->model, $user), $file);
        $this->afterImportHook($request);

        return $this->success(__('NaiveCrud::messages.imported'));
    }

    public function importTemplate(Request $request): Response
    {
        $this->can($this->getImportAbility());

        $name = Str::snake(Str::pluralStudly(class_basename($this->model)));
        if (method_exists($this->model, 'importFields')) {
            $fields = $this->model::importFields();

            return Excel::download(new TemplateExport($fields), "{$name}-template.csv");
        }

        throw new NCException(400, "Model $name doesnt have template fields");
    }
}
