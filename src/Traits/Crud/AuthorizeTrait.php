<?php

namespace Aldeebhasan\NaiveCrud\Traits\Crud;

use Illuminate\Foundation\Auth\User;

trait AuthorizeTrait
{
    protected bool $authorize = true;

    private ?User $user;

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
        if (! $this->authorize && empty($ability)) {
            return true;
        }

        if ($this->resolveUser() && $this->resolveUser()->can($ability, $model ?? $this->model)) {
            return true;
        }
        abort(403, __('NaiveCrud::messages.unauthorized'));
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

    public function resolveUser(): ?User
    {
        $this->user ??= auth()->guard(config('naive-crud.auth_guard'))->user();

        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
