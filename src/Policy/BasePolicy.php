<?php

namespace Aldeebhasan\NaiveCrud\Policy;

use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    protected string $key;

    protected string $keyDelimiter = '_';

    public function __construct()
    {
        if (! isset($this->key)) {
            $modelClass = class_basename(static::class);
            $modelClass = str_replace('Policy', '', $modelClass);
            $modelClass = str($modelClass)->plural()->snake($this->keyDelimiter)->toString();
            $this->key = $modelClass;
        }
    }

    public function before(Model $user): bool|null
    {
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function index(Model $user): bool
    {
        return $this->userCan('index', $user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(Model $user, mixed $model = null): bool
    {
        return $this->userCan('show', $user, $model);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user): bool
    {
        return $this->userCan('create', $user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, mixed $model = null): bool
    {
        return $this->userCan('update', $user, $model);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, mixed $model = null): bool
    {
        return $this->userCan('delete', $user, $model);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Model $user, mixed $model = null): bool
    {
        return $this->userCan('restore', $user, $model);
    }

    /**
     * Determine whether the user can permanently delete the post.
     */
    public function forceDelete(Model $user, mixed $model = null): bool
    {
        return $this->userCan('forceDelete', $user, $model);
    }

    /**
     * Determine whether the user can export the models.
     */
    public function export(Model $user): bool
    {
        return $this->userCan('export', $user);
    }

    /**
     * Determine whether the user can import the models.
     */
    public function import(Model $user): bool
    {
        return $this->userCan('import', $user);
    }

    public function doesOwnItem(Model $user, mixed $model = null): bool
    {
        return true;
    }

    protected function userCan(string $action, Model $user, mixed $model = null, string $key = null): bool
    {
        $policyKey = sprintf('%s_%s', $action, $this->getKey($key, $model));

        return $user->can($policyKey) && $this->doesOwnItem($user, $model);
    }

    protected function getKey(string $key = null, mixed $model = null): string
    {
        return $key ?? $this->key;
    }
}
