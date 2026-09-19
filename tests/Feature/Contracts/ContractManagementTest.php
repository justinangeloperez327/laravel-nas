<?php

namespace Tests\Feature\Contracts;

use App\Modules\Clients\Database\Seeders\ClientAccessSeeder;
use App\Modules\Clients\Models\Client;
use App\Modules\Contracts\Database\Seeders\ContractAccessSeeder;
use App\Modules\Organization\Database\Seeders\OrganizationAccessSeeder;
use App\Modules\Organization\Models\Company;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_create_contract(): void
    {
        $administrator = $this->administrator();
        $client = Client::query()->create(['name' => 'Client', 'code' => 'CL', 'is_active' => true]);
        $company = Company::query()->create(['name' => 'NAS', 'code' => 'NAS', 'is_active' => true]);

        $this->actingAs($administrator)->post('/contracts', [
            'client_id' => $client->id, 'company_id' => $company->id,
            'contract_number' => 'C-001', 'title' => 'Main Contract', 'scope' => null,
            'currency' => 'AED', 'contract_value' => 1000000,
            'retention_percentage' => 10, 'advance_percentage' => 10,
            'start_date' => null, 'completion_date' => null,
            'status' => 'draft', 'payment_terms' => null,
        ])->assertRedirect();

        $this->assertDatabaseHas('contracts', ['contract_number' => 'C-001']);
    }

    private function administrator(): User
    {
        $this->seed(UserAccessSeeder::class);
        $this->seed(OrganizationAccessSeeder::class);
        $this->seed(ClientAccessSeeder::class);
        $this->seed(ContractAccessSeeder::class);
        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'system-administrator')->firstOrFail();
        $user->roles()->sync([$role->id]);
        return $user;
    }
}
