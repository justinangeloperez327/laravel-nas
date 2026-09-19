<?php

namespace App\Modules\DocumentControl\Actions;

use App\Modules\Approvals\Actions\SubmitForApproval;
use App\Modules\DocumentControl\Models\DocumentRevision;
use App\Modules\Users\Models\User;
use Illuminate\Validation\ValidationException;

class SubmitDocumentRevision
{
    public function __construct(private readonly SubmitForApproval $submitForApproval) {}

    public function execute(DocumentRevision $revision, User $user): DocumentRevision
    {
        if ($revision->status !== 'draft') {
            throw ValidationException::withMessages(['revision' => 'Only draft revisions can be submitted.']);
        }

        $this->submitForApproval->execute('DOCUMENT-APPROVAL', 'document_revision', $revision->id, $user);

        $revision->update(['status' => 'submitted', 'submitted_at' => now()]);

        return $revision->refresh();
    }
}
