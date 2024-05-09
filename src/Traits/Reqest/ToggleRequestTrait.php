<?php

namespace Aldeebhasan\NaiveCrud\Traits\Reqest;

trait ToggleRequestTrait
{
    /**
     * Rules for the "toggle" endpoint.
     */
    public function toggleRules(): array
    {
        return [];
    }

    /**
     * Messages for the "toggle" (PUT) endpoint.
     */
    public function toggleMessages(): array
    {
        return [];
    }
}
