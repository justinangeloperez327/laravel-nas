<?php

namespace App\Modules\Suppliers\Http\Requests;

use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can($this->isMethod('post') ? 'suppliers.create' : 'suppliers.update') ?? false;
    }

    public function rules(): array
    {
        /** @var Supplier|null $supplier */
        $supplier = $this->route('supplier');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:100', Rule::unique('suppliers', 'code')->ignore($supplier?->id)],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'trade_license_number' => ['nullable', 'string', 'max:100'],
            'trade_license_expiry' => ['nullable', 'date'],
            'tax_registration_number' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'status' => ['required', Rule::in(['pending', 'approved', 'suspended', 'inactive'])],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:supplier_categories,id'],
        ];
    }
}
