<?php

namespace Aldeebhasan\NaiveCrud\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseForm extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if (! $this->route()) {
            return [];
        }

        if ($this->route()->getActionMethod() === 'store') {
            return array_merge($this->commonRules(), $this->storeRules());
        }

        if ($this->route()->getActionMethod() === 'bulkStore') {
            return $this->buildBulkRules($this->storeRules(), $this->bulkStoreRules());
        }

        if ($this->route()->getActionMethod() === 'update') {
            return array_merge($this->commonRules(), $this->updateRules());
        }

        if ($this->route()->getActionMethod() === 'bulkUpdate') {
            return $this->buildBulkRules($this->updateRules(), $this->bulkUpdateRules());
        }

        if ($this->route()->getActionMethod() === 'toggle') {
            return array_merge(
                [
                    'resources' => ['array', 'required'],
                ],
                $this->toggleRules()
            );
        }

        return [];
    }

    /**
     * Get custom attributes for validator errors that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        if (! $this->route()) {
            return [];
        }

        if ($this->route()->getActionMethod() === 'store') {
            return array_merge($this->commonMessages(), $this->storeMessages());
        }

        if ($this->route()->getActionMethod() === 'bulkStore') {
            return array_merge($this->commonMessages(), $this->storeMessages(), $this->bulkStoreMessages());
        }

        if ($this->route()->getActionMethod() === 'update') {
            return array_merge($this->commonMessages(), $this->updateMessages());
        }

        if ($this->route()->getActionMethod() === 'bulkUpdate') {
            return array_merge($this->commonMessages(), $this->updateMessages(), $this->bulkUpdateMessages());
        }

        if ($this->route()->getActionMethod() === 'toggle') {
            return $this->toggleMessages();
        }

        return [];
    }

    /**
     * Default rules for the request.
     *
     * @return array
     */
    public function commonRules(): array
    {
        return [];
    }

    /**
     * Rules for the "store" (POST) endpoint.
     *
     * @return array
     */
    public function storeRules(): array
    {
        return [];
    }

    protected function buildBulkRules(array $definedRules, array $definedBatchRules): array
    {
        $batchRules = [
            'resources' => ['array', 'required'],
        ];

        $mergedRules = array_merge($this->commonRules(), $definedRules, $definedBatchRules);

        foreach ($mergedRules as $ruleField => $fieldRules) {
            $batchRules["resources.*.{$ruleField}"] = $fieldRules;
        }

        return $batchRules;
    }

    /**
     * Rules for the "batch store" (POST) endpoint.
     *
     * @return array
     */
    public function bulkStoreRules(): array
    {
        return [];
    }

    /**
     * Rules for the "update" (PATCH|PUT) endpoint.
     *
     * @return array
     */
    public function updateRules(): array
    {
        return [];
    }

    /**
     * Rules for the "batch update" (PATCH|PUT) endpoint.
     *
     * @return array
     */
    public function bulkUpdateRules(): array
    {
        return [];
    }

    /**
     * Rules for the "associate" endpoint.
     *
     * @return array
     */

    /**
     * Rules for the "toggle" endpoint.
     *
     * @return array
     */
    public function toggleRules(): array
    {
        return [];
    }

    /**
     * Default messages for the request.
     *
     * @return array
     */
    public function commonMessages(): array
    {
        return [];
    }

    /**
     * Messages for the "store" (POST) endpoint.
     *
     * @return array
     */
    public function storeMessages(): array
    {
        return [];
    }

    /**
     * Messages for the "batchstore" (POST) endpoint.
     *
     * @return array
     */
    public function bulkStoreMessages(): array
    {
        return [];
    }

    /**
     * Messages for the "update" (POST) endpoint.
     *
     * @return array
     */
    public function updateMessages(): array
    {
        return [];
    }

    /**
     * Messages for the "batchUpdate" (POST) endpoint.
     *
     * @return array
     */
    public function bulkUpdateMessages(): array
    {
        return [];
    }

    /**
     * Messages for the "toggle" endpoint.
     *
     * @return array
     */
    public function toggleMessages(): array
    {
        return [];
    }
}
