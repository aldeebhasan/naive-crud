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
 */
trait HooksTrait
{
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this, $name)) {
            $this->$name(...$arguments);
        }
        throw new \BadMethodCallException('Method ' . $name . ' does not exist.');
    }
}
