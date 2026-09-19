<?php

namespace Tests\Feature\Organization;

use App\Modules\Organization\Database\Seeders\OrganizationAccessSeeder;
use App\Modules\Organization\Models\Company;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_permission_cannot_view_organization(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/administration/organization')
            ->assertForbidden();
    }

    public function test_administrator_can_create_company(): void
    {
        $administrator = $this->administrator();

        $this->actingAs($administrator)
            ->post('/administration/organization/companies', [
                'name' => 'Noor Al Sahara',
                'code' => 'NAS',
                'legal_name' => 'Noor Al Sahara General Contracting LLC',
                'is_active' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('companies', [
            'code' => 'NAS',
            'name' => 'Noor Al Sahara',
        ]);
    }

    public function test_business_unit_code_is_unique_within_company(): void
    {
        $administrator = $this->administrator();
        $company = Company::query()->create([
            'name' => 'Noor Al Sahara',
            'code' => 'NAS',
            'is_active' => true,
        ]);

        $payload = [
            'company_id' => $company->id,
            'name' => 'Construction',
            'code' => 'CON',
            'is_active' => true,
        ];

        $this->actingAs($administrator)
            ->post('/administration/organization/business-units', $payload)
            ->assertRedirect();

        $this->actingAs($administrator)
            ->post('/administration/organization/business-units', $payload)
            ->assertSessionHasErrors('code');
    }

    public function test_administrator_can_deactivate_company(): void
    {
        $administrator = $this->administrator();
        $company = Company::query()->create([
            'name' => 'Noor Al Sahara',
            'code' => 'NAS',
            'is_active' => true,
        ]);

        $this->actingAs($administrator)
            ->patch("/administration/organization/companies/{$company->id}/status", [
                'is_active' => false,
            ])
            ->assertRedirect();

        $this->assertFalse($company->fresh()->is_active);
    }

    private function administrator(): User
    {
        $this->seed(UserAccessSeeder::class);
        $this->seed(OrganizationAccessSeeder::class);

        $user = User::factory()->create();

        $role = Role::query()
            ->where('slug', 'system-administrator')
            ->firstOrFail();

        $user->roles()->sync([$role->id]);

        return $user;
    }
}
