<?php

namespace Aldeebhasan\NaiveCrud\Actions;

use Aldeebhasan\NaiveCrud\Contracts\ActionUI;
use Illuminate\Database\Eloquent\Model;

abstract class BaseAction implements ActionUI
{
    protected string $modelClass;

    protected ?Model $model = null;

    protected array $data = [];

    public function setModelClass(string $modelClass): self
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    abstract public function handle(): Model;
}
