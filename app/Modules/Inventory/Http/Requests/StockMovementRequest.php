<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            in_array($this->string('movement_type')->toString(), ['positive-adjustment', 'negative-adjustment'], true)
                ? 'inventory.adjust'
                : 'inventory.move',
        ) ?? false;
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'inventory_item_id' => ['required', 'integer', 'exists:inventory_items,id'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'movement_type' => ['required', Rule::in(['receipt', 'issue', 'return', 'transfer-in', 'transfer-out', 'positive-adjustment', 'negative-adjustment'])],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'reference_type' => ['nullable', 'string', 'max:100'],
            'reference_id' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
            'occurred_at' => ['nullable', 'date'],
        ];
    }
}
