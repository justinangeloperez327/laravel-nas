<?php

namespace App\Modules\Procurement\Models;

use App\Modules\Approvals\Models\ApprovalRequest;
use App\Modules\Projects\Models\Project;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequest extends Model
{
    protected $fillable = [
        'project_id',
        'requested_by_user_id',
        'approval_request_id',
        'request_number',
        'request_date',
        'required_by_date',
        'purpose',
        'currency',
        'total_estimated_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'request_date' => 'date',
            'required_by_date' => 'date',
            'total_estimated_amount' => 'decimal:2',
        ];
    }

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by_user_id'); }
    public function approvalRequest(): BelongsTo { return $this->belongsTo(ApprovalRequest::class); }
    public function items(): HasMany { return $this->hasMany(PurchaseRequestItem::class); }
    public function purchaseOrders(): HasMany { return $this->hasMany(PurchaseOrder::class); }
}
