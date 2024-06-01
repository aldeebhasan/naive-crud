<?php

namespace Aldeebhasan\NaiveCrud\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ActionUI
{
    public function handle(): Model;
}
