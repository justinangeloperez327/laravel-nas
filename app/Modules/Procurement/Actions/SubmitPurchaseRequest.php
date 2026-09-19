<?php

namespace App\Modules\Procurement\Actions;

use App\Modules\Approvals\Actions\SubmitForApproval;
use App\Modules\Procurement\Models\PurchaseRequest;
use App\Modules\Users\Models\User;
use Illuminate\Validation\ValidationException;

class SubmitPurchaseRequest
{
    public function __construct(private readonly SubmitForApproval $submitForApproval) {}

    public function execute(PurchaseRequest $purchaseRequest, User $user): PurchaseRequest
    {
        if ($purchaseRequest->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft purchase requests can be submitted.']);
        }

        $approval = $this->submitForApproval->execute(
            'PURCHASE-REQUEST-APPROVAL',
            'purchase_request',
            $purchaseRequest->id,
            $user,
            $purchaseRequest->total_estimated_amount,
        );

        $purchaseRequest->update([
            'approval_request_id' => $approval->id,
            'status' => 'pending-approval',
        ]);

        return $purchaseRequest->refresh();
    }
}
