<?php

namespace Aldeebhasan\NaiveCrud\Logic\Resolvers;

use Aldeebhasan\NaiveCrud\Actions\CreatAction;
use Aldeebhasan\NaiveCrud\Actions\DeleteAction;
use Aldeebhasan\NaiveCrud\Actions\UpdateAction;
use Aldeebhasan\NaiveCrud\Http\Requests\BaseRequest;
use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

/**@method  static ComponentResolver make(string $modelClass) */
class ComponentResolver
{
    use Makable;

    protected string $requestClassesNamespace = 'App\\Http\\Requests\\';

    protected string $resourceClassesNamespace = 'App\\Http\\Resources\\';

    protected string $actionClassesNamespace = 'App\\Actions\\';

    public function __construct(protected string $modelClass)
    {
    }

    public function resolveRequestForm(?string $requestForm = null): string
    {
        if ($requestForm && class_exists($requestForm)) {
            return $requestForm;
        }
        $requestClassName = sprintf(
            '%s%s%s',
            $this->requestClassesNamespace,
            class_basename($this->modelClass),
            'Request'
        );

        if (class_exists($requestClassName)) {
            return $requestClassName;
        }

        return BaseRequest::class;
    }

    public function resolveModelResource(?string $resourceClass = null): string
    {
        if ($resourceClass && class_exists($resourceClass)) {
            return $resourceClass;
        }
        $requestClassName = sprintf(
            '%s%s%s',
            $this->resourceClassesNamespace,
            class_basename($this->modelClass),
            'Resource'
        );

        if (class_exists($requestClassName)) {
            return $requestClassName;
        }

        return BaseResource::class;
    }

    public function resolveModelAction(string $action, ?string $actionClass = null): ?string
    {
        if ($actionClass && class_exists($actionClass)) {
            return $actionClass;
        }
        $requestClassName = sprintf(
            '%s%s%s%s',
            $this->actionClassesNamespace,
            $action,
            class_basename($this->modelClass),
            'Action'
        );

        if (class_exists($requestClassName)) {
            return $requestClassName;
        }

        return match ($action) {
            'create', 'bulkCreate' => CreatAction::class,
            'update', 'bulkUpdate' => UpdateAction::class,
            'delete', 'bulkDelete' => DeleteAction::class,
        };
    }

    public function bindRequestForm(string $requestClass): void
    {
        App::bind(Request::class, $requestClass);
    }

    public function bindPolicy(string $policy): void
    {
        Gate::policy($this->modelClass, $policy);
    }
}
