<?php

namespace App\Modules\Organization\Http\Requests;

use App\Modules\Organization\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
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
        /** @var Location|null $location */
        $location = $this->route('location');
        $companyId = $this->integer('company_id');

        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('locations', 'code')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($location?->id),
            ],
            'type' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
