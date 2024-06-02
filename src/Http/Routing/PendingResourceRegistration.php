<?php

namespace Aldeebhasan\NaiveCrud\Http\Routing;

use Illuminate\Routing\PendingResourceRegistration as basePendingResourceRegistration;
use Illuminate\Support\Arr;

class PendingResourceRegistration extends basePendingResourceRegistration
{
    /**
     * Disables bulk operations on the resource.
     *
     * @return $this
     */
    public function withoutBulk(): self
    {
        $except = Arr::get($this->options, 'except', []);

        $except = array_merge($except, ['bulkStore', 'bulkUpdate', 'bulkDestroy']);

        $this->except($except);

        return $this;
    }

    /**
     * Disables the search operation on the resource.
     *
     * @return $this
     */
    public function withoutSearch(): self
    {
        $except = Arr::get($this->options, 'except', []);

        $except = array_merge($except, ['search']);

        $this->except($except);

        return $this;
    }

    /**
     * Disables the search operation on the resource.
     *
     * @return $this
     */
    public function withoutImportExport(): self
    {
        $except = Arr::get($this->options, 'except', []);

        $except = array_merge($except, ['import', 'importTemplate', 'export']);

        $this->except($except);

        return $this;
    }
}
