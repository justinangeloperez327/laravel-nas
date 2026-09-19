<?php

namespace App\Modules\Procurement\Actions;

use App\Modules\Procurement\Models\PurchaseOrder;
use App\Modules\Procurement\Models\PurchaseRequest;
use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreatePurchaseOrder
{
    public function execute(array $data): PurchaseOrder
    {
        $purchaseRequest = PurchaseRequest::query()->findOrFail($data['purchase_request_id']);
        $supplier = Supplier::query()->findOrFail($data['supplier_id']);

        if ($purchaseRequest->status !== 'approved') {
            throw ValidationException::withMessages(['purchase_request_id' => 'Purchase Order requires an approved Purchase Request.']);
        }

        if ($supplier->status !== 'approved') {
            throw ValidationException::withMessages(['supplier_id' => 'Purchase Order requires an approved supplier.']);
        }

        $items = collect($data['items'])->map(function (array $item): array {
            $item['total'] = round((float) $item['quantity'] * (float) $item['unit_price'], 2);

            return $item;
        });
        $total = (float) $items->sum('total');

        if ($total > (float) $purchaseRequest->total_estimated_amount) {
            throw ValidationException::withMessages([
                'items' => 'Purchase Order value cannot exceed the approved Purchase Request value without re-approval.',
            ]);
        }

        return DB::transaction(function () use ($data, $purchaseRequest, $items, $total): PurchaseOrder {
            $purchaseOrder = PurchaseOrder::query()->create([
                'project_id' => $purchaseRequest->project_id,
                'purchase_request_id' => $purchaseRequest->id,
                'supplier_id' => $data['supplier_id'],
                'purchase_order_number' => $data['purchase_order_number'],
                'order_date' => $data['order_date'],
                'currency' => $data['currency'],
                'total_amount' => $total,
                'status' => 'draft',
            ]);

            $purchaseOrder->items()->createMany($items->all());

            return $purchaseOrder->load('items');
        });
    }
}
