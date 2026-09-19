<?php

namespace App\Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = ['purchase_order_id', 'description', 'quantity', 'unit', 'unit_price', 'total'];

    protected function casts(): array
    {
        return ['quantity' => 'decimal:4', 'unit_price' => 'decimal:4', 'total' => 'decimal:2'];
    }

    public function purchaseOrder(): BelongsTo { return $this->belongsTo(PurchaseOrder::class); }
}
