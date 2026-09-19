<?php

namespace App\Modules\DocumentControl\Database\Seeders;

use App\Modules\Approvals\Models\ApprovalWorkflow;
use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class DocumentControlAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View documents', 'slug' => 'documents.view'],
            ['name' => 'Create documents', 'slug' => 'documents.create'],
            ['name' => 'Submit document revisions', 'slug' => 'documents.submit'],
            ['name' => 'Approve document revisions', 'slug' => 'documents.approve'],
            ['name' => 'Manage transmittals', 'slug' => 'documents.manage-transmittals'],
        ])->map(fn (array $permission): Permission => Permission::query()->updateOrCreate(['slug' => $permission['slug']], $permission));

        $administrator = Role::query()->where('slug', 'system-administrator')->first();
        if ($administrator) $administrator->permissions()->syncWithoutDetaching($permissions->pluck('id'));

        $workflow = ApprovalWorkflow::query()->updateOrCreate(
            ['code' => 'DOCUMENT-APPROVAL'],
            ['name' => 'Document Approval', 'entity_type' => 'document_revision', 'description' => 'Default document revision approval workflow.', 'is_active' => true],
        );

        $workflow->steps()->updateOrCreate(
            ['sequence' => 1],
            ['name' => 'Document Approval', 'approver_type' => 'permission', 'approver_reference' => 'documents.approve'],
        );
    }
}
