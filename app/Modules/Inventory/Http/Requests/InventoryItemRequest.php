<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('inventory.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'max:100', 'unique:inventory_items,sku'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'reorder_level' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
