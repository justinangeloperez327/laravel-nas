<?php

namespace App\Modules\Procurement\Database\Seeders;

use App\Modules\Approvals\Models\ApprovalWorkflow;
use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class ProcurementAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View procurement', 'slug' => 'procurement.view'],
            ['name' => 'Create purchase requests', 'slug' => 'purchase-requests.create'],
            ['name' => 'Approve purchase requests', 'slug' => 'purchase-requests.approve'],
            ['name' => 'Manage requests for quotation', 'slug' => 'rfq.manage'],
            ['name' => 'Create purchase orders', 'slug' => 'purchase-orders.create'],
            ['name' => 'Approve purchase orders', 'slug' => 'purchase-orders.approve'],
            ['name' => 'Manage goods receipts', 'slug' => 'goods-receipts.manage'],
        ])->map(fn (array $permission): Permission => Permission::query()->updateOrCreate(['slug' => $permission['slug']], $permission));

        $administrator = Role::query()->where('slug', 'system-administrator')->first();

        if ($administrator) {
            $administrator->permissions()->syncWithoutDetaching($permissions->pluck('id'));
        }

        $purchaseRequestWorkflow = ApprovalWorkflow::query()->updateOrCreate(
            ['code' => 'PURCHASE-REQUEST-APPROVAL'],
            ['name' => 'Purchase Request Approval', 'entity_type' => 'purchase_request', 'is_active' => true],
        );
        $purchaseRequestWorkflow->steps()->updateOrCreate(
            ['sequence' => 1],
            ['name' => 'Purchase Request Approval', 'approver_type' => 'permission', 'approver_reference' => 'purchase-requests.approve'],
        );

        $purchaseOrderWorkflow = ApprovalWorkflow::query()->updateOrCreate(
            ['code' => 'PURCHASE-ORDER-APPROVAL'],
            ['name' => 'Purchase Order Approval', 'entity_type' => 'purchase_order', 'is_active' => true],
        );
        $purchaseOrderWorkflow->steps()->updateOrCreate(
            ['sequence' => 1],
            ['name' => 'Purchase Order Approval', 'approver_type' => 'permission', 'approver_reference' => 'purchase-orders.approve'],
        );
    }
}
