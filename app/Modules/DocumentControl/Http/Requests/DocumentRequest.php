<?php

namespace App\Modules\DocumentControl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('documents.create') ?? false; }

    public function rules(): array
    {
        return [
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'contract_id' => ['nullable', 'integer', 'exists:contracts,id'],
            'document_number' => ['required', 'string', 'max:150', Rule::unique('documents', 'document_number')],
            'title' => ['required', 'string', 'max:255'],
            'document_type' => ['required', 'string', 'max:100'],
            'discipline' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['draft', 'active', 'closed', 'cancelled'])],
        ];
    }
}
