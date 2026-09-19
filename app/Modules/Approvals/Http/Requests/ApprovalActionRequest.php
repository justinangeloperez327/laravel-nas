<?php

namespace App\Modules\Approvals\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovalActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('approvals.act') ?? false;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['approved', 'rejected', 'returned'])],
            'comments' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
