<?php

namespace Aldeebhasan\NaiveCrud\Traits\Reqest;

trait StoreRequestTrait
{
    /**
     * Rules for the "store" (POST) endpoint.
     */
    public function storeRules(): array
    {
        return [];
    }

    /**
     * Rules for the "batch store" (POST) endpoint.
     * @return array
     */
    public function bulkStoreRules(): array
    {
        return [];
    }

    /**
     * Messages for the "store" (POST) endpoint.
     */
    public function storeMessages(): array
    {
        return [];
    }

    /**
     * Messages for the "bulkStore" (POST) endpoint.
     */
    public function bulkStoreMessages(): array
    {
        return [];
    }
}
