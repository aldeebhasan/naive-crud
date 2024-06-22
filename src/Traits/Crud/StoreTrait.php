<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

trait StoreTrait
{
    protected ?string $createAction = null;

    protected ?string $bulkCreateAction = null;

    /** @param BaseRequest $request */
    public function store(Request $request): Response|Responsable
    {
        $this->can($this->getCreateAbility());
        $data = $request->validated();
        $data = array_merge($data, $this->extraStoreData());

        $this->beforeStoreHook($request);

        $action = $this->resolveCreateAction();
        $item = App::make($action)->setModelClass($this->model)->setData($data)->handle();

        $this->afterStoreHook($request, $item);

        $data = $this->formatCreateItem($item);

        return $this->storeResponse(__('NaiveCrud::messages.stored'), $data);
    }

    protected function storeResponse(string $message, array $data): Response|Responsable
    {
        return $this->success($data, $message, 201);
    }

    /** @param BaseRequest $request */
    public function bulkStore(Request $request): Response|Responsable
    {
        $this->can($this->getCreateAbility());
        $data = $request->validated();

        $this->beforeBulkStoreHook($request);

        $count = 0;
        $action = $this->resolveBulkCreateAction();
        foreach ($data['resources'] as $itemData) {
            $itemData = array_merge($itemData, $this->extraStoreData());
            App::make($action)->setModelClass($this->model)->setData($itemData)->handle();
            $count++;
        }

        $this->afterBulkStoreHook($request);

        return $this->bulkStoreResponse(__('NaiveCrud::messages.bulk-stored', ['count' => $count]));
    }

    protected function bulkStoreResponse(string $message): Response|Responsable
    {
        return $this->success(message: $message, status: 201);
    }

    protected function extraStoreData(): array
    {
        return [];
    }

    protected function formatCreateItem(Model $item): array
    {
        $resource = $this->modelResource ?? BaseResource::class;

        return $resource::makeCustom($item, $this->resolveUser(), false)->resolve();
    }

    private function resolveCreateAction(): string
    {
        return $this->componentsResolver->resolveModelAction('create', $this->createAction);
    }

    private function resolveBulkCreateAction(): string
    {
        return $this->componentsResolver->resolveModelAction('bulkCreate', $this->bulkCreateAction ?? $this->createAction);
    }
}
