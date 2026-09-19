<?php

namespace App\Modules\HumanResources\Actions;

use App\Modules\Approvals\Actions\SubmitForApproval;
use App\Modules\HumanResources\Models\LeaveRequest;
use App\Modules\Users\Models\User;
use Illuminate\Validation\ValidationException;

class SubmitLeaveRequest
{
    public function __construct(private readonly SubmitForApproval $submitForApproval) {}

    public function execute(LeaveRequest $leaveRequest, User $user): LeaveRequest
    {
        if ($leaveRequest->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft leave requests can be submitted.']);
        }

        $approval = $this->submitForApproval->execute(
            'LEAVE-APPROVAL',
            'leave_request',
            $leaveRequest->id,
            $user,
        );

        $leaveRequest->update(['approval_request_id' => $approval->id, 'status' => 'pending-approval']);

        return $leaveRequest->refresh();
    }
}
