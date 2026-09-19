<?php

namespace Tests\Feature\Projects;

use App\Modules\Clients\Database\Seeders\ClientAccessSeeder;
use App\Modules\Clients\Models\Client;
use App\Modules\Organization\Database\Seeders\OrganizationAccessSeeder;
use App\Modules\Organization\Models\Company;
use App\Modules\Projects\Database\Seeders\ProjectAccessSeeder;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_create_project(): void
    {
        $administrator = $this->administrator();
        $client = Client::query()->create(['name' => 'Client', 'code' => 'CL-1', 'is_active' => true]);
        $company = Company::query()->create(['name' => 'NAS', 'code' => 'NAS', 'is_active' => true]);

        $this->actingAs($administrator)->post('/projects', [
            'client_id' => $client->id,
            'company_id' => $company->id,
            'business_unit_id' => null,
            'location_id' => null,
            'project_number' => 'PRJ-001',
            'name' => 'Test Project',
            'description' => null,
            'status' => 'planned',
            'start_date' => null,
            'planned_completion_date' => null,
            'actual_completion_date' => null,
            'contract_value' => null,
            'progress_percentage' => 0,
        ])->assertRedirect();

        $this->assertDatabaseHas('projects', ['project_number' => 'PRJ-001']);
    }

    public function test_user_without_permission_cannot_view_projects(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/projects')->assertForbidden();
    }

    private function administrator(): User
    {
        $this->seed(UserAccessSeeder::class);
        $this->seed(OrganizationAccessSeeder::class);
        $this->seed(ClientAccessSeeder::class);
        $this->seed(ProjectAccessSeeder::class);
        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'system-administrator')->firstOrFail();
        $user->roles()->sync([$role->id]);
        return $user;
    }
}
