<?php

namespace App\Modules\DocumentControl\Actions;

use App\Modules\Approvals\Actions\RecordApprovalAction;
use App\Modules\Approvals\Models\ApprovalRequest;
use App\Modules\DocumentControl\Models\DocumentRevision;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;

class DecideDocumentRevision
{
    public function __construct(private readonly RecordApprovalAction $recordApprovalAction) {}

    public function execute(DocumentRevision $revision, User $user, string $action, ?string $comments = null): DocumentRevision
    {
        $approval = ApprovalRequest::query()
            ->where('entity_type', 'document_revision')
            ->where('entity_id', $revision->id)
            ->where('status', 'pending')
            ->latest('id')
            ->firstOrFail();

        $result = $this->recordApprovalAction->execute($approval, $user, $action, $comments);

        return DB::transaction(function () use ($revision, $user, $result, $comments): DocumentRevision {
            if ($result->status === 'approved') {
                DocumentRevision::query()
                    ->where('document_id', $revision->document_id)
                    ->where('id', '!=', $revision->id)
                    ->where('status', 'approved')
                    ->update(['status' => 'superseded']);

                $revision->update([
                    'status' => 'approved',
                    'decided_by_user_id' => $user->id,
                    'decided_at' => now(),
                    'decision_comments' => $comments,
                ]);
            } elseif ($result->status === 'rejected') {
                $revision->update([
                    'status' => 'rejected',
                    'decided_by_user_id' => $user->id,
                    'decided_at' => now(),
                    'decision_comments' => $comments,
                ]);
            } elseif ($result->status === 'returned') {
                $revision->update([
                    'status' => 'draft',
                    'decided_by_user_id' => $user->id,
                    'decided_at' => now(),
                    'decision_comments' => $comments,
                ]);
            }

            return $revision->refresh();
        });
    }
}
