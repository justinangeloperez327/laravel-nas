<?php

namespace App\Modules\Clients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('clients.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'is_primary' => ['required', 'boolean'],
        ];
    }
}
