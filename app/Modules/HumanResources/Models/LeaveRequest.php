<?php

namespace App\Modules\HumanResources\Models;

use App\Modules\Approvals\Models\ApprovalRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = ['employee_id', 'approval_request_id', 'leave_type', 'starts_on', 'ends_on', 'total_days', 'reason', 'status'];

    protected function casts(): array
    {
        return ['starts_on' => 'date', 'ends_on' => 'date', 'total_days' => 'decimal:2'];
    }

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function approvalRequest(): BelongsTo { return $this->belongsTo(ApprovalRequest::class); }
}
