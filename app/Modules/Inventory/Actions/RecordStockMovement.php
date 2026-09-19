<?php

namespace App\Modules\Inventory\Actions;

use App\Modules\Inventory\Models\StockBalance;
use App\Modules\Inventory\Models\StockMovement;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecordStockMovement
{
    public function execute(array $data, User $user): StockMovement
    {
        return DB::transaction(function () use ($data, $user): StockMovement {
            $balance = StockBalance::query()
                ->lockForUpdate()
                ->firstOrCreate(
                    ['warehouse_id' => $data['warehouse_id'], 'inventory_item_id' => $data['inventory_item_id']],
                    ['quantity_on_hand' => 0, 'quantity_reserved' => 0],
                );

            $quantity = (float) $data['quantity'];
            $delta = match ($data['movement_type']) {
                'receipt', 'return', 'transfer-in', 'positive-adjustment' => $quantity,
                'issue', 'transfer-out', 'negative-adjustment' => -$quantity,
                default => throw ValidationException::withMessages(['movement_type' => 'Unsupported stock movement type.']),
            };

            $newQuantity = (float) $balance->quantity_on_hand + $delta;

            if ($newQuantity < 0) {
                throw ValidationException::withMessages(['quantity' => 'Stock movement would create a negative stock balance.']);
            }

            $balance->update(['quantity_on_hand' => $newQuantity]);

            return StockMovement::query()->create([
                ...$data,
                'performed_by_user_id' => $user->id,
                'occurred_at' => $data['occurred_at'] ?? now(),
            ]);
        });
    }
}
