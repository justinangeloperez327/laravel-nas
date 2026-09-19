<?php

namespace App\Modules\Procurement\Actions;

use App\Modules\Approvals\Actions\RecordApprovalAction;
use App\Modules\Procurement\Models\PurchaseRequest;
use App\Modules\Users\Models\User;

class DecidePurchaseRequest
{
    public function __construct(private readonly RecordApprovalAction $recordApprovalAction) {}

    public function execute(PurchaseRequest $purchaseRequest, User $user, string $action, ?string $comments = null): PurchaseRequest
    {
        $approval = $purchaseRequest->approvalRequest()->firstOrFail();
        $result = $this->recordApprovalAction->execute($approval, $user, $action, $comments);

        $status = match ($result->status) {
            'approved' => 'approved',
            'rejected' => 'rejected',
            'returned' => 'draft',
            default => 'pending-approval',
        };

        $purchaseRequest->update(['status' => $status]);

        return $purchaseRequest->refresh();
    }
}
