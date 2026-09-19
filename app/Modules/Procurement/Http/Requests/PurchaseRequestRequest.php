<?php

namespace App\Modules\Procurement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('purchase-requests.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'request_number' => ['required', 'string', 'max:100', 'unique:purchase_requests,request_number'],
            'request_date' => ['required', 'date'],
            'required_by_date' => ['nullable', 'date', 'after_or_equal:request_date'],
            'purpose' => ['nullable', 'string'],
            'currency' => ['required', 'string', 'size:3'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit' => ['required', 'string', 'max:50'],
            'items.*.estimated_unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
