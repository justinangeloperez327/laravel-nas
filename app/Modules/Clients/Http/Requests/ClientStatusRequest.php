<?php

namespace App\Modules\Clients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('clients.change-status') ?? false;
    }

    public function rules(): array
    {
        return ['is_active' => ['required', 'boolean']];
    }
}
