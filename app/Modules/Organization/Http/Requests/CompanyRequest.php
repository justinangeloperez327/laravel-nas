<?php

namespace App\Modules\Organization\Http\Requests;

use App\Modules\Organization\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
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
        /** @var Company|null $company */
        $company = $this->route('company');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('companies', 'code')->ignore($company?->id),
            ],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
