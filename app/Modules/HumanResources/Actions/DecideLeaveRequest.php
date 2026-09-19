<?php

namespace App\Modules\HumanResources\Actions;

use App\Modules\Approvals\Actions\RecordApprovalAction;
use App\Modules\HumanResources\Models\LeaveRequest;
use App\Modules\Users\Models\User;

class DecideLeaveRequest
{
    public function __construct(private readonly RecordApprovalAction $recordApprovalAction) {}

    public function execute(LeaveRequest $leaveRequest, User $user, string $action, ?string $comments = null): LeaveRequest
    {
        $result = $this->recordApprovalAction->execute(
            $leaveRequest->approvalRequest()->firstOrFail(),
            $user,
            $action,
            $comments,
        );

        $leaveRequest->update([
            'status' => match ($result->status) {
                'approved' => 'approved',
                'rejected' => 'rejected',
                'returned' => 'draft',
                default => 'pending-approval',
            },
        ]);

        return $leaveRequest->refresh();
    }
}
