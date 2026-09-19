<?php

namespace App\Modules\Organization\Http\Requests;

use App\Modules\Organization\Models\BusinessUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessUnitRequest extends FormRequest
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
        /** @var BusinessUnit|null $businessUnit */
        $businessUnit = $this->route('businessUnit');
        $companyId = $this->integer('company_id');

        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('business_units', 'code')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($businessUnit?->id),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
