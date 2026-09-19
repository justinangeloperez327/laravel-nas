<?php

namespace Tests\Feature\Procurement;

use App\Modules\Approvals\Database\Seeders\ApprovalAccessSeeder;
use App\Modules\Clients\Models\Client;
use App\Modules\Organization\Models\Company;
use App\Modules\Procurement\Actions\CreatePurchaseOrder;
use App\Modules\Procurement\Actions\CreatePurchaseRequest;
use App\Modules\Procurement\Actions\DecidePurchaseRequest;
use App\Modules\Procurement\Actions\SubmitPurchaseRequest;
use App\Modules\Procurement\Database\Seeders\ProcurementAccessSeeder;
use App\Modules\Projects\Models\Project;
use App\Modules\Suppliers\Models\Supplier;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_order_cannot_exceed_approved_request(): void
    {
        $this->seed(UserAccessSeeder::class);
        $this->seed(ApprovalAccessSeeder::class);
        $this->seed(ProcurementAccessSeeder::class);

        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'system-administrator')->firstOrFail();
        $user->roles()->sync([$role->id]);

        $client = Client::query()->create(['name' => 'Client', 'code' => 'CL', 'is_active' => true]);
        $company = Company::query()->create(['name' => 'NAS', 'code' => 'NAS', 'is_active' => true]);
        $project = Project::query()->create([
            'client_id' => $client->id, 'company_id' => $company->id,
            'project_number' => 'PRJ', 'name' => 'Project', 'status' => 'active',
            'progress_percentage' => 0,
        ]);
        $supplier = Supplier::query()->create(['name' => 'Supplier', 'code' => 'SUP', 'status' => 'approved']);

        $pr = app(CreatePurchaseRequest::class)->execute([
            'project_id' => $project->id, 'request_number' => 'PR-1',
            'request_date' => now()->toDateString(), 'currency' => 'AED',
            'items' => [['description' => 'Material', 'quantity' => 1, 'unit' => 'ea', 'estimated_unit_price' => 100]],
        ], $user);

        app(SubmitPurchaseRequest::class)->execute($pr, $user);
        app(DecidePurchaseRequest::class)->execute($pr->fresh(), $user, 'approved');

        $this->expectException(ValidationException::class);

        app(CreatePurchaseOrder::class)->execute([
            'purchase_request_id' => $pr->id,
            'supplier_id' => $supplier->id,
            'purchase_order_number' => 'PO-1',
            'order_date' => now()->toDateString(),
            'currency' => 'AED',
            'items' => [['description' => 'Material', 'quantity' => 2, 'unit' => 'ea', 'unit_price' => 100]],
        ]);
    }
}
