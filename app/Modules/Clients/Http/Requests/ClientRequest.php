<?php

namespace App\Modules\Clients\Http\Requests;

use App\Modules\Clients\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can($this->isMethod('post') ? 'clients.create' : 'clients.update') ?? false;
    }

    public function rules(): array
    {
        /** @var Client|null $client */
        $client = $this->route('client');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('clients', 'code')->ignore($client?->id)],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'tax_registration_number' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
