<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @method  beforeIndexHook(Request $request)
 * @method  afterIndexHook(Request $request)
 * @method  beforeSearchHook(Request $request)
 * @method  afterSearchHook(Request $request)
 * @method  beforeShowHook(Request $request, Model $model)
 * @method  afterShowHook(Request $request, Model $model)
 * @method  beforeUpdateHook(Request $request, Model $model)
 * @method  afterUpdateHook(Request $request, Model $model)
 * @method  beforeStoreHook(Request $request, Model $model)
 * @method  afterStoreHook(Request $request, Model $model)
 * @method  beforeDeleteHook(Request $request, Model $model)
 * @method  afterDeleteHook(Request $request, Model $model)
 * @method  beforeExportHook(Request $request)
 * @method  afterExportHook(Request $request)
 * @method  beforeImportHook(Request $request)
 * @method  afterImportHook(Request $request)
 * @method  beforeBulkStoreHook(Request $request)
 * @method  afterBulkStoreHook(Request $request)
 * @method  beforeBulkUpdateHook(Request $request)
 * @method  afterBulkUpdateHook(Request $request)
 * @method  beforeBulkDeleteHook(Request $request)
 * @method  afterBulkDeleteHook(Request $request)
 * @method  beforeToggleHook(Request $request)
 * @method  afterToggleHook(Request $request)
 */
trait HooksTrait
{
    public function __call($name, $arguments): mixed
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        return null;
    }
}
