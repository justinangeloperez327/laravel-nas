<?php

namespace App\Modules\Organization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('organization.change-status') ?? false;
    }

    /**
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [
            'is_active' => ['required', 'boolean'],
        ];
    }
}
