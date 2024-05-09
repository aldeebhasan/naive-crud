<?php

namespace Aldeebhasan\NaiveCrud\Traits\Reqest;

trait UpdateRequestTrait
{
    /**
     * Rules for the "update" (PATCH|PUT) endpoint.
     */
    public function updateRules(): array
    {
        return [];
    }

    /**
     * Rules for the "batch update" (PATCH|PUT) endpoint.
     * @return array
     */
    public function bulkUpdateRules(): array
    {
        return [];
    }

    /**
     * Messages for the "update" (PUT) endpoint.
     */
    public function updateMessages(): array
    {
        return [];
    }

    /**
     * Messages for the "batchUpdate" (PUT) endpoint.
     */
    public function bulkUpdateMessages(): array
    {
        return [];
    }
}
