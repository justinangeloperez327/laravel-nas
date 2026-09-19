<?php

namespace App\Modules\Procurement\Models;

use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestForQuotation extends Model
{
    protected $fillable = ['purchase_request_id', 'rfq_number', 'issue_date', 'closing_date', 'status'];

    protected function casts(): array { return ['issue_date' => 'date', 'closing_date' => 'date']; }

    public function purchaseRequest(): BelongsTo { return $this->belongsTo(PurchaseRequest::class); }
    public function suppliers(): BelongsToMany { return $this->belongsToMany(Supplier::class, 'request_for_quotation_supplier')->withPivot('status'); }
    public function quotations(): HasMany { return $this->hasMany(SupplierQuotation::class); }
}
