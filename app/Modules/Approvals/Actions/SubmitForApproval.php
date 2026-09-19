<?php

namespace App\Modules\Approvals\Actions;

use App\Modules\Approvals\Models\ApprovalRequest;
use App\Modules\Approvals\Models\ApprovalWorkflow;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubmitForApproval
{
    public function execute(
        string $workflowCode,
        string $entityType,
        int $entityId,
        User $submitter,
        ?string $amount = null,
    ): ApprovalRequest {
        $workflow = ApprovalWorkflow::query()
            ->with('steps')
            ->where('code', $workflowCode)
            ->where('entity_type', $entityType)
            ->where('is_active', true)
            ->firstOrFail();

        if (ApprovalRequest::query()
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->where('status', 'pending')
            ->exists()) {
            throw ValidationException::withMessages([
                'approval' => 'This record already has a pending approval request.',
            ]);
        }

        $steps = $workflow->steps->filter(
            fn ($step): bool => $step->appliesToAmount($amount),
        );

        if ($steps->isEmpty()) {
            throw ValidationException::withMessages([
                'approval' => 'No approval steps apply to this request.',
            ]);
        }

        return DB::transaction(fn (): ApprovalRequest => ApprovalRequest::query()->create([
            'approval_workflow_id' => $workflow->id,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'submitted_by_user_id' => $submitter->id,
            'amount' => $amount,
            'status' => 'pending',
            'current_step_sequence' => $steps->first()->sequence,
            'submitted_at' => now(),
        ]));
    }
}
