<?php

namespace App\Modules\Organization\Http\Requests;

use App\Modules\Organization\Models\CostCenter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CostCenterRequest extends FormRequest
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
        /** @var CostCenter|null $costCenter */
        $costCenter = $this->route('costCenter');
        $companyId = $this->integer('company_id');

        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cost_centers', 'code')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($costCenter?->id),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
