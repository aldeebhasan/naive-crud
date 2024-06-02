<?php

namespace Aldeebhasan\NaiveCrud\Http\Routing;

use  Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;
use Illuminate\Routing\Route;

class NCResourceRegistrar extends BaseResourceRegistrar
{
    protected $resourceDefaults = [
        'search', 'import', 'importTemplate', 'export',
        'bulkStore', 'bulkUpdate', 'bulkDestroy',
        'fields', 'toggle', 'index', 'store', 'show', 'update', 'destroy',
    ];

    protected function addResourceFields(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/fields';

        $action = $this->getResourceAction($name, $controller, 'fields', $options);

        return $this->router->get($uri, $action);

    }

    protected function addResourceToggle(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/toggle';

        $action = $this->getResourceAction($name, $controller, 'toggle', $options);

        return $this->router->put($uri, $action);

    }

    protected function addResourceSearch(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/search';

        $action = $this->getResourceAction($name, $controller, 'search', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceImport(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/import';

        $action = $this->getResourceAction($name, $controller, 'import', $options);

        return $this->router->post($uri, $action);
    }

    protected function addResourceImportTemplate(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/import-template';

        $action = $this->getResourceAction($name, $controller, 'importTemplate', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceExport(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/export';

        $action = $this->getResourceAction($name, $controller, 'export', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceBulkStore(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/bulk';

        $action = $this->getResourceAction($name, $controller, 'bulkStore', $options);

        return $this->router->post($uri, $action);
    }

    protected function addResourceBulkUpdate(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/bulk';

        $action = $this->getResourceAction($name, $controller, 'bulkUpdate', $options);

        return $this->router->put($uri, $action);
    }

    protected function addResourceBulkDestroy(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name).'/bulk';

        $action = $this->getResourceAction($name, $controller, 'bulkDestroy', $options);

        return $this->router->delete($uri, $action);
    }
}
