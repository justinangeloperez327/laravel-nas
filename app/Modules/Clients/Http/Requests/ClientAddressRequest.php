<?php

namespace App\Modules\Clients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('clients.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'type' => ['required', 'string', 'max:50'],
            'line_1' => ['required', 'string', 'max:255'],
            'line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:30'],
        ];
    }
}
