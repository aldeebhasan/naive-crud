<?php

namespace Aldeebhasan\NaiveCrud\Actions;

use Illuminate\Database\Eloquent\Model;

class UpdateAction extends BaseAction
{
    public function handle(): Model
    {
        $model = $this->model;
        $model->update($this->data);

        return $model;

    }
}
