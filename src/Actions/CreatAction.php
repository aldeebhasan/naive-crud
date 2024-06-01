<?php

namespace Aldeebhasan\NaiveCrud\Actions;

use Illuminate\Database\Eloquent\Model;

class CreatAction extends BaseAction
{
    public function handle(): Model
    {
        $item = new $this->modelClass($this->data);
        $item->save();

        return $item;
    }
}
