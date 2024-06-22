<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait ToggleTrait
{
    /** @param BaseRequest $request */
    public function toggle(Request $request): Response|Responsable
    {
        $this->can($this->getUpdateAbility());

        $data = $request->validated();

        $query = $this->baseQueryResolver($request)->build();

        $this->beforeToggleHook($request);

        $ids = $data['resources'] ?? [];
        unset($data['resources']);
        $count = $query->whereKey($ids)->update($data);

        $this->afterToggleHook($request);

        return $this->toggleResponse(__('NaiveCrud::messages.bulk-updated', ['count' => $count]));
    }

    protected function toggleResponse(string $message): Response|Responsable
    {
        return $this->success(message: $message);
    }
}
