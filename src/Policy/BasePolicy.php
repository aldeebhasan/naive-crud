<?php

namespace Aldeebhasan\NaiveCrud\Policy;

use Illuminate\Database\Eloquent\Model;

class BasePolicy
{

    protected string $key;
    protected string $keyDelimiter = '_';

    public function __construct()
    {
        if (!isset($this->key)) {
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
        return $this->checkIfCan("index", $user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param mixed|null $model
     */
    public function show(Model $user, mixed $model = null): bool
    {
        return $this->checkIfCan('show', $user, $model);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user): bool
    {
        return $this->checkIfCan("create", $user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param Model $user
     * @param mixed $model
     * @return bool
     */
    public function update(Model $user, mixed $model = null): bool
    {
        return $this->checkIfCan('update', $user, $model);
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param Model $user
     * @param mixed $model
     * @return bool
     */
    public function delete(Model $user, mixed $model = null): bool
    {
        return $this->checkIfCan('delete', $user, $model);
    }


    /**
     * Determine whether the user can restore the model.
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function restore(Model $user, mixed $model = null): bool
    {
        return $this->checkIfCan('restore', $user, $model);
    }

    /**
     * Determine whether the user can permanently delete the post.
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function forceDelete(Model $user, mixed $model = null): bool
    {
        return $this->checkIfCan('forceDelete', $user, $model);
    }

    /**
     * Determine whether the user can export the models.
     *
     *
     * @return mixed
     */
    public function export(Model $user): bool
    {
        return $this->checkIfCan("export", $user);
    }


    /**
     * Determine whether the user can import the models.
     *
     *
     * @return mixed
     */
    public function import(Model $user): bool
    {
        return $this->checkIfCan("import", $user);
    }

    public function doesOwnItem(Model $user, mixed $model = null): bool
    {
        return true;
    }

    protected function checkIfCan(string $action, Model $user, mixed $model = null, string $key = null): bool
    {
        $policyKey = sprintf("%s_%s", $action, $this->getKey($key, $model));
        return $user->can($policyKey) && $this->doesOwnItem($user, $model);
    }

    protected function getKey(string $key = null, mixed $model = null): string
    {
        return $key ?? $this->key;
    }

}