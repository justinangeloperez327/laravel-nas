<?php

namespace App\Modules\Approvals\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovalStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('approvals.configure') ?? false;
    }

    public function rules(): array
    {
        return [
            'approval_workflow_id' => ['required', 'integer', 'exists:approval_workflows,id'],
            'sequence' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'approver_type' => ['required', Rule::in(['permission', 'role', 'user'])],
            'approver_reference' => ['required', 'string', 'max:255'],
            'minimum_amount' => ['nullable', 'numeric', 'min:0'],
            'maximum_amount' => ['nullable', 'numeric', 'gte:minimum_amount'],
        ];
    }
}
