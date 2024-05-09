<?php

namespace Aldeebhasan\NaiveCrud\Logic\Resolvers;

use Aldeebhasan\NaiveCrud\Http\Requests\BaseForm;
use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Aldeebhasan\NaiveCrud\Traits\Makable;

/**@method  static ComponentResolver make(string $modelClass) */
class ComponentResolver
{
    use Makable;

    protected string $requestClassesNamespace = 'App\\Http\\Requests\\';

    protected string $resourceClassesNamespace = 'App\\Http\\Resources\\';

    public function __construct(protected string $modelClass)
    {
    }

    public function resolveRequestForm(?string $requestForm = null): string
    {
        if ($requestForm && class_exists($requestForm)) {
            return $requestForm;
        }
        $requestClassName = sprintf('%s%s%s', $this->requestClassesNamespace, class_basename($this->modelClass), 'Request');

        if (class_exists($requestClassName)) {
            return $requestClassName;
        }

        return BaseForm::class;
    }

    public function resolveModelResource(?string $resourceClass = null): string
    {
        if ($resourceClass && class_exists($resourceClass)) {
            return $resourceClass;
        }
        $requestClassName = sprintf('%s%s%s', $this->resourceClassesNamespace, class_basename($this->modelClass), 'Resources');

        if (class_exists($requestClassName)) {
            return $requestClassName;
        }

        return BaseResource::class;
    }
}
