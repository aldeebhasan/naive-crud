<?php

namespace Aldeebhasan\NaiveCrud\Actions;

use Illuminate\Database\Eloquent\Model;

class DeleteAction extends BaseAction
{
    public function handle(): Model
    {
        $model = $this->model;
        $model->delete();

        return $model;
    }
}
