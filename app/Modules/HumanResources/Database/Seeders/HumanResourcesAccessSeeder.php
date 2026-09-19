<?php

namespace App\Modules\HumanResources\Database\Seeders;

use App\Modules\Approvals\Models\ApprovalWorkflow;
use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class HumanResourcesAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View Human Resources', 'slug' => 'human-resources.view'],
            ['name' => 'Manage employees', 'slug' => 'human-resources.manage'],
            ['name' => 'Manage attendance', 'slug' => 'human-resources.attendance'],
            ['name' => 'Manage timesheets', 'slug' => 'human-resources.timesheets'],
            ['name' => 'Approve leave requests', 'slug' => 'human-resources.approve-leave'],
            ['name' => 'View sensitive employee data', 'slug' => 'human-resources.view-sensitive'],
        ])->map(fn (array $permission): Permission => Permission::query()->updateOrCreate(
            ['slug' => $permission['slug']],
            $permission,
        ));

        $administrator = Role::query()->where('slug', 'system-administrator')->first();

        if ($administrator) {
            $administrator->permissions()->syncWithoutDetaching($permissions->pluck('id'));
        }

        $workflow = ApprovalWorkflow::query()->updateOrCreate(
            ['code' => 'LEAVE-APPROVAL'],
            ['name' => 'Leave Approval', 'entity_type' => 'leave_request', 'is_active' => true],
        );

        $workflow->steps()->updateOrCreate(
            ['sequence' => 1],
            ['name' => 'Leave Approval', 'approver_type' => 'permission', 'approver_reference' => 'human-resources.approve-leave'],
        );
    }
}
