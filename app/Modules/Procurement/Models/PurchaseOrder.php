<?php

namespace App\Modules\Procurement\Models;

use App\Modules\Approvals\Models\ApprovalRequest;
use App\Modules\Projects\Models\Project;
use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'project_id', 'purchase_request_id', 'supplier_id', 'approval_request_id',
        'purchase_order_number', 'order_date', 'currency', 'total_amount', 'status',
    ];

    protected function casts(): array
    {
        return ['order_date' => 'date', 'total_amount' => 'decimal:2'];
    }

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function purchaseRequest(): BelongsTo { return $this->belongsTo(PurchaseRequest::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function approvalRequest(): BelongsTo { return $this->belongsTo(ApprovalRequest::class); }
    public function items(): HasMany { return $this->hasMany(PurchaseOrderItem::class); }
}
