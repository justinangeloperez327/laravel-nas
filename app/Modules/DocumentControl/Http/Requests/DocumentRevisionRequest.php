<?php

namespace App\Modules\DocumentControl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentRevisionRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('documents.create') ?? false; }

    public function rules(): array
    {
        return [
            'document_id' => ['required', 'integer', 'exists:documents,id'],
            'revision_code' => ['required', 'string', 'max:50'],
            'storage_provider' => ['nullable', Rule::in(['sharepoint', 'azure-blob', 'local', 'external'])],
            'external_file_id' => ['nullable', 'string', 'max:255'],
            'storage_path' => ['nullable', 'string', 'max:255'],
            'original_filename' => ['nullable', 'string', 'max:255'],
            'mime_type' => ['nullable', 'string', 'max:150'],
            'file_size' => ['nullable', 'integer', 'min:0'],
            'checksum' => ['nullable', 'string', 'max:128'],
        ];
    }
}
