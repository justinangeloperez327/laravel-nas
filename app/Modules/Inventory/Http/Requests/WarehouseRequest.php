<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('inventory.manage') ?? false;
    }

    public function rules(): array
    {
        $companyId = $this->integer('company_id');

        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'code' => ['required', 'string', 'max:100', Rule::unique('warehouses', 'code')->where('company_id', $companyId)],
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
