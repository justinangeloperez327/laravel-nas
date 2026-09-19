<?php

namespace App\Modules\Approvals\Models;

use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalWorkflowStep extends Model
{
    protected $fillable = [
        'approval_workflow_id', 'sequence', 'name', 'approver_type',
        'approver_reference', 'minimum_amount', 'maximum_amount',
    ];

    protected function casts(): array
    {
        return ['minimum_amount' => 'decimal:2', 'maximum_amount' => 'decimal:2'];
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    public function appliesToAmount(?string $amount): bool
    {
        if ($amount === null) {
            return $this->minimum_amount === null && $this->maximum_amount === null;
        }

        $value = (float) $amount;

        return ($this->minimum_amount === null || $value >= (float) $this->minimum_amount)
            && ($this->maximum_amount === null || $value <= (float) $this->maximum_amount);
    }

    public function canBeActedBy(User $user): bool
    {
        return match ($this->approver_type) {
            'permission' => $user->hasPermission($this->approver_reference),
            'role' => $user->roles()->where('slug', $this->approver_reference)->exists(),
            'user' => $user->id === (int) $this->approver_reference,
            default => false,
        };
    }
}
