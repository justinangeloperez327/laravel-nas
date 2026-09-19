<?php

namespace Tests\Feature\Suppliers;

use App\Modules\Suppliers\Database\Seeders\SupplierAccessSeeder;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_create_and_approve_supplier(): void
    {
        $this->seed(UserAccessSeeder::class);
        $this->seed(SupplierAccessSeeder::class);
        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'system-administrator')->firstOrFail();
        $user->roles()->sync([$role->id]);

        $this->actingAs($user)->post('/suppliers', [
            'name' => 'Supplier One', 'code' => 'SUP-001', 'legal_name' => null,
            'trade_license_number' => null, 'trade_license_expiry' => null,
            'tax_registration_number' => null, 'email' => null, 'phone' => null,
            'website' => null, 'status' => 'pending', 'category_ids' => [],
        ])->assertRedirect();

        $supplierId = (int) \DB::table('suppliers')->value('id');
        $this->actingAs($user)->post("/suppliers/{$supplierId}/approve")->assertRedirect();
        $this->assertDatabaseHas('suppliers', ['id' => $supplierId, 'status' => 'approved']);
    }
}
