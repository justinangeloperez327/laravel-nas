<?php

namespace App\Modules\Procurement\Actions;

use App\Modules\Procurement\Models\PurchaseRequest;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;

class CreatePurchaseRequest
{
    public function execute(array $data, User $user): PurchaseRequest
    {
        return DB::transaction(function () use ($data, $user): PurchaseRequest {
            $items = collect($data['items'])->map(function (array $item): array {
                $item['estimated_total'] = round((float) $item['quantity'] * (float) $item['estimated_unit_price'], 2);

                return $item;
            });

            $purchaseRequest = PurchaseRequest::query()->create([
                'project_id' => $data['project_id'],
                'requested_by_user_id' => $user->id,
                'request_number' => $data['request_number'],
                'request_date' => $data['request_date'],
                'required_by_date' => $data['required_by_date'] ?? null,
                'purpose' => $data['purpose'] ?? null,
                'currency' => $data['currency'],
                'total_estimated_amount' => $items->sum('estimated_total'),
                'status' => 'draft',
            ]);

            $purchaseRequest->items()->createMany($items->all());

            return $purchaseRequest->load('items');
        });
    }
}
