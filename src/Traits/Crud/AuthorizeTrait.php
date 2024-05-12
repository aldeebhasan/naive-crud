<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

trait AuthorizeTrait
{
    protected bool $authorize = true;

    protected string|array $indexAbility = 'index';

    protected string|array $showAbility = 'show';

    protected string|array $createAbility = 'create';

    protected string|array $updateAbility = 'update';

    protected string|array $deleteAbility = 'delete';

    protected string|array $restoreAbility = 'restore';

    protected string|array $forceDeleteAbility = 'forceDelete';

    protected string|array $importAbility = 'import';

    protected string|array $exportAbility = 'export';

    protected function can(string|array $ability, $model = null): bool
    {
        if ($this->authorize && !empty($ability)) {
            if ($this->getUser() && $this->getUser()->can($ability, $model ?? $this->model)) {
                return true;
            }
            abort(403, __('NaiveCrud::messages.unauthorized'));
        }

        return true;
    }

    /**
     * @return array|string
     */
    public function getIndexAbility(): array|string
    {
        return $this->indexAbility;
    }

    /**
     * @return array|string
     */
    public function getShowAbility(): array|string
    {
        return $this->showAbility;
    }

    /**
     * @return array|string
     */
    public function getCreateAbility(): array|string
    {
        return $this->createAbility;
    }

    /**
     * @return array|string
     */
    public function getUpdateAbility(): array|string
    {
        return $this->updateAbility;
    }

    /**
     * @return array|string
     */
    public function getDeleteAbility(): array|string
    {
        return $this->deleteAbility;
    }

    /**
     * @return array|string
     */
    public function getRestoreAbility(): array|string
    {
        return $this->restoreAbility;
    }

    /**
     * @return array|string
     */
    public function getForceDeleteAbility(): array|string
    {
        return $this->forceDeleteAbility;
    }

    /**
     * @return array|string
     */
    public function getImportAbility(): array|string
    {
        return $this->importAbility;
    }

    /**
     * @return array|string
     */
    public function getExportAbility(): array|string
    {
        return $this->exportAbility;
    }
}
