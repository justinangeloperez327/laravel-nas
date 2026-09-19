<?php

namespace App\Modules\Organization\Http\Requests;

use App\Modules\Organization\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            $this->isMethod('post') ? 'organization.create' : 'organization.update',
        ) ?? false;
    }

    /**
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        /** @var Department|null $department */
        $department = $this->route('department');
        $businessUnitId = $this->integer('business_unit_id');

        return [
            'business_unit_id' => ['required', 'integer', 'exists:business_units,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('departments', 'code')
                    ->where(fn ($query) => $query->where('business_unit_id', $businessUnitId))
                    ->ignore($department?->id),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
