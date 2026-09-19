<?php

namespace App\Modules\Contracts\Http\Requests;

use App\Modules\Contracts\Models\Contract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can($this->isMethod('post') ? 'contracts.create' : 'contracts.update') ?? false;
    }

    public function rules(): array
    {
        /** @var Contract|null $contract */
        $contract = $this->route('contract');

        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'contract_number' => ['required', 'string', 'max:100', Rule::unique('contracts', 'contract_number')->ignore($contract?->id)],
            'title' => ['required', 'string', 'max:255'],
            'scope' => ['nullable', 'string'],
            'currency' => ['required', 'string', 'size:3'],
            'contract_value' => ['required', 'numeric', 'min:0'],
            'retention_percentage' => ['required', 'numeric', 'between:0,100'],
            'advance_percentage' => ['required', 'numeric', 'between:0,100'],
            'start_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(['draft', 'active', 'completed', 'terminated', 'cancelled'])],
            'payment_terms' => ['nullable', 'string'],
        ];
    }
}
