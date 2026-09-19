<?php

namespace Tests\Feature\Approvals;

use App\Modules\Approvals\Actions\RecordApprovalAction;
use App\Modules\Approvals\Actions\SubmitForApproval;
use App\Modules\Approvals\Database\Seeders\ApprovalAccessSeeder;
use App\Modules\Approvals\Models\ApprovalWorkflow;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_based_approval_can_complete_request(): void
    {
        $this->seed(UserAccessSeeder::class);
        $this->seed(ApprovalAccessSeeder::class);

        $role = Role::query()->where('slug', 'system-administrator')->firstOrFail();
        $user = User::factory()->create();
        $user->roles()->sync([$role->id]);

        $workflow = ApprovalWorkflow::query()->create([
            'name' => 'Test Approval',
            'code' => 'TEST',
            'entity_type' => 'test',
            'is_active' => true,
        ]);

        $workflow->steps()->create([
            'sequence' => 1,
            'name' => 'Approve',
            'approver_type' => 'permission',
            'approver_reference' => 'approvals.act',
        ]);

        $request = app(SubmitForApproval::class)->execute('TEST', 'test', 10, $user);
        $result = app(RecordApprovalAction::class)->execute($request, $user, 'approved');

        $this->assertSame('approved', $result->status);
        $this->assertNotNull($result->completed_at);
    }
}
