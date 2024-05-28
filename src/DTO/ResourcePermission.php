<?php

namespace Aldeebhasan\NaiveCrud\DTO;

use Aldeebhasan\NaiveCrud\Traits\Makable;
use Spatie\Permission\Models\Permission;

/**@method  static ResourcePermission make(string $guard, string $resource, array $actions = []) */
final readonly class ResourcePermission
{
    use Makable;

    public string $resource;

    public function __construct(
        public string $guard,
        string $resource,
        public array $actions
    )
    {
        $modelClass = class_basename($resource);
        $this->resource = str($modelClass)->plural()->snake('_')->toString();
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
