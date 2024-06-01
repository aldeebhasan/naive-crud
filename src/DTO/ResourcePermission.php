<?php

namespace Aldeebhasan\NaiveCrud\DTO;

use Aldeebhasan\NaiveCrud\Traits\Makable;
use Spatie\Permission\Models\Permission;

/**@method  static ResourcePermission make(string $guard, string $resource, array|string $actions = []) */
final readonly class ResourcePermission
{
    use Makable;

    public string $resource;

    public array|string $actions;

    public function __construct(
        public string $guard,
        string $resource,
        array|string $actions
    )
    {
        $modelClass = class_basename($resource);
        $this->resource = str($modelClass)->plural()->snake('_')->toString();
        $this->actions = is_string($actions) ? [$actions] : $actions;
    }

    public function register(): void
    {
        foreach ($this->actions as $action) {
            $permissionName = "{$action}_{$this->resource}";
            Permission::updateOrCreate([
                'name' => $permissionName,
                'guard_name' => $this->guard,
            ]);
        }
    }
}
