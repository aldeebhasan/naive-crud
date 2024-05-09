<?php

namespace Aldeebhasan\NaiveCrud\Http\Requests;

use Aldeebhasan\NaiveCrud\Traits\Reqest\StoreRequestTrait;
use Aldeebhasan\NaiveCrud\Traits\Reqest\ToggleRequestTrait;
use Aldeebhasan\NaiveCrud\Traits\Reqest\UpdateRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    use StoreRequestTrait, UpdateRequestTrait, ToggleRequestTrait;

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

        if ($this->route()->getActionMethod() === 'bulkDestroy') {
            return [
                'resources' => 'required|array|min:1',
                'resources.*' => 'required',
            ];
        }

        if ($this->route()->getActionMethod() === 'toggle') {
            return array_merge(
                [
                    'resources' => 'required|array|min:1',
                    'resources.*' => 'required',
                ],
                $this->toggleRules()
            );
        }

        if ($this->route()->getActionMethod() === 'export') {
            return [
                'type' => 'nullable|in:excel,csv,html',
                'target' => 'nullable|in:all,page',
            ];
        }
        if ($this->route()->getActionMethod() === 'import') {
            return [
                'file' => 'required|string',
            ];
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
     * Default rules for the request.
     */
    public function commonRules(): array
    {
        return [];
    }

    /**
     * Default messages for the request.
     */
    public function commonMessages(): array
    {
        return [];
    }
}
