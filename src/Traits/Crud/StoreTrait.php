<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

trait StoreTrait
{
    protected ?string $createAction = null;

    protected ?string $bulkCreateAction = null;

    /** @param BaseRequest $request */
    public function store(Request $request): Response
    {
        $this->can($this->getCreateAbility());
        $data = $request->validated();
        $data = array_merge($data, $this->extraStoreData());

        $this->beforeStoreHook($request);

        $action = $this->resolveCreateAction();
        $item = App::make($action)->setModelClass($this->model)->setData($data)->handle();

        $this->afterStoreHook($request, $item);

        $data = $this->formatCreateResponse($item);

        return $this->success($data, __('NaiveCrud::messages.stored'), 201);
    }

    /** @param BaseRequest $request */
    public function bulkStore(Request $request): Response
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

        return $this->success(message: __('NaiveCrud::messages.bulk-stored', ['count' => $count]), status: 201);
    }

    protected function extraStoreData(): array
    {
        return [];
    }

    protected function formatCreateResponse(Model $item): array
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
