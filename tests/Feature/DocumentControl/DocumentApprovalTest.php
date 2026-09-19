<?php

namespace Tests\Feature\DocumentControl;

use App\Modules\Approvals\Database\Seeders\ApprovalAccessSeeder;
use App\Modules\DocumentControl\Actions\DecideDocumentRevision;
use App\Modules\DocumentControl\Actions\SubmitDocumentRevision;
use App\Modules\DocumentControl\Database\Seeders\DocumentControlAccessSeeder;
use App\Modules\DocumentControl\Models\Document;
use App\Modules\DocumentControl\Models\DocumentRevision;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_revision_can_be_submitted_and_approved(): void
    {
        $this->seed(UserAccessSeeder::class);
        $this->seed(ApprovalAccessSeeder::class);
        $this->seed(DocumentControlAccessSeeder::class);

        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'system-administrator')->firstOrFail();
        $user->roles()->sync([$role->id]);

        $document = Document::query()->create([
            'document_number' => 'DOC-001', 'title' => 'Drawing',
            'document_type' => 'Drawing', 'status' => 'active',
        ]);
        $revision = DocumentRevision::query()->create([
            'document_id' => $document->id, 'revision_code' => 'A',
            'status' => 'draft', 'created_by_user_id' => $user->id,
        ]);

        app(SubmitDocumentRevision::class)->execute($revision, $user);
        $this->assertSame('submitted', $revision->fresh()->status);

        app(DecideDocumentRevision::class)->execute($revision->fresh(), $user, 'approved');
        $this->assertSame('approved', $revision->fresh()->status);
    }
}
