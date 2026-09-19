<?php

namespace App\Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequestItem extends Model
{
    protected $fillable = ['purchase_request_id', 'description', 'quantity', 'unit', 'estimated_unit_price', 'estimated_total'];

    protected function casts(): array
    {
        return ['quantity' => 'decimal:4', 'estimated_unit_price' => 'decimal:4', 'estimated_total' => 'decimal:2'];
    }

    public function purchaseRequest(): BelongsTo { return $this->belongsTo(PurchaseRequest::class); }
}
