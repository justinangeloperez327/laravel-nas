<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockBalance extends Model
{
    protected $fillable = ['warehouse_id', 'inventory_item_id', 'quantity_on_hand', 'quantity_reserved'];

    protected function casts(): array
    {
        return ['quantity_on_hand' => 'decimal:4', 'quantity_reserved' => 'decimal:4'];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
