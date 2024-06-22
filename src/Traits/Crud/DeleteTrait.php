<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

trait DeleteTrait
{
    protected ?string $deleteAction = null;

    protected ?string $bulkDeleteAction = null;

    public function destroy(Request $request, $id): Response|Responsable
    {

        $query = $this->baseQueryResolver($request)->build();

        $item = $query->findOrFail($id);
        $this->can($this->getDeleteAbility(), $item);

        $this->beforeDeleteHook($request, $item);

        $action = $this->resolveDeleteAction();
        App::make($action)->setModel($item)->handle();

        $this->afterDeleteHook($request, $item);

        return $this->destroyResponse(__('NaiveCrud::messages.deleted'));
    }

    protected function destroyResponse(string $message): Response|Responsable
    {
        return $this->success(message: $message);
    }

    public function bulkDestroy(Request $request): Response|Responsable
    {
        $this->can($this->getDeleteAbility());

        $validated = $request->validate([
            'resources' => 'required|array|min:1',
            'resources.*' => 'required|integer',
        ]);

        $query = $this->baseQueryResolver($request)->build();

        $this->beforeBulkDeleteHook($request);

        $ids = $validated['resources'];
        $action = $this->resolveBulkDeleteAction();
        $items = $query->whereKey($ids)->get()->each(fn ($item) => App::make($action)->setModel($item)->handle());

        $this->afterBulkDeleteHook($request);

        return $this->bulkDestroyResponse(__('NaiveCrud::messages.bulk-deleted', ['count' => $items->count()]));
    }

    protected function bulkDestroyResponse(string $message): Response|Responsable
    {
        return $this->success(message: $message);
    }

    private function resolveDeleteAction(): string
    {
        return $this->componentsResolver->resolveModelAction('delete', $this->deleteAction);
    }

    private function resolveBulkDeleteAction(): string
    {
        return $this->componentsResolver->resolveModelAction('bulkDelete', $this->bulkDeleteAction ?? $this->deleteAction);
    }
}
