<?php

namespace App\Modules\Procurement\Models;

use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierQuotation extends Model
{
    protected $fillable = ['request_for_quotation_id', 'supplier_id', 'quotation_number', 'quotation_date', 'currency', 'total_amount', 'lead_time_days', 'valid_until', 'status'];

    protected function casts(): array
    {
        return ['quotation_date' => 'date', 'total_amount' => 'decimal:2', 'valid_until' => 'date'];
    }

    public function requestForQuotation(): BelongsTo { return $this->belongsTo(RequestForQuotation::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
}
