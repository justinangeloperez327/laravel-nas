<?php

namespace App\Modules\Approvals\Actions;

use App\Modules\Approvals\Models\ApprovalAction;
use App\Modules\Approvals\Models\ApprovalRequest;
use App\Modules\Approvals\Models\ApprovalWorkflowStep;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecordApprovalAction
{
    public function execute(
        ApprovalRequest $approvalRequest,
        User $actor,
        string $action,
        ?string $comments = null,
    ): ApprovalRequest {
        if ($approvalRequest->status !== 'pending') {
            throw ValidationException::withMessages(['approval' => 'This approval request is already closed.']);
        }

        $workflow = $approvalRequest->workflow()->with('steps')->firstOrFail();

        /** @var ApprovalWorkflowStep $step */
        $step = $workflow->steps->firstWhere('sequence', $approvalRequest->current_step_sequence);

        if (! $step || ! $step->canBeActedBy($actor)) {
            throw ValidationException::withMessages(['approval' => 'You are not authorized for the current approval step.']);
        }

        if (! in_array($action, ['approved', 'rejected', 'returned'], true)) {
            throw ValidationException::withMessages(['action' => 'Invalid approval action.']);
        }

        return DB::transaction(function () use ($approvalRequest, $actor, $action, $comments, $workflow, $step): ApprovalRequest {
            ApprovalAction::query()->create([
                'approval_request_id' => $approvalRequest->id,
                'approval_workflow_step_id' => $step->id,
                'actor_user_id' => $actor->id,
                'action' => $action,
                'comments' => $comments,
                'acted_at' => now(),
            ]);

            if ($action !== 'approved') {
                $approvalRequest->update([
                    'status' => $action,
                    'completed_at' => now(),
                ]);

                return $approvalRequest->refresh();
            }

            $next = $workflow->steps
                ->filter(fn ($candidate): bool => $candidate->sequence > $step->sequence)
                ->filter(fn ($candidate): bool => $candidate->appliesToAmount($approvalRequest->amount))
                ->first();

            if ($next) {
                $approvalRequest->update(['current_step_sequence' => $next->sequence]);
            } else {
                $approvalRequest->update([
                    'status' => 'approved',
                    'current_step_sequence' => null,
                    'completed_at' => now(),
                ]);
            }

            return $approvalRequest->refresh();
        });
    }
}
