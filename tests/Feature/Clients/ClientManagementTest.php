<?php

namespace Tests\Feature\Clients;

use App\Modules\Clients\Database\Seeders\ClientAccessSeeder;
use App\Modules\Clients\Models\Client;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_permission_cannot_view_clients(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/clients')->assertForbidden();
    }

    public function test_administrator_can_create_client(): void
    {
        $administrator = $this->administrator();

        $this->actingAs($administrator)->post('/clients', [
            'name' => 'Example Client',
            'code' => 'CLIENT-001',
            'legal_name' => 'Example Client LLC',
            'tax_registration_number' => null,
            'website' => null,
            'is_active' => true,
        ])->assertRedirect();

        $this->assertDatabaseHas('clients', ['code' => 'CLIENT-001']);
    }

    public function test_administrator_can_add_client_contact(): void
    {
        $administrator = $this->administrator();
        $client = Client::query()->create([
            'name' => 'Example Client',
            'code' => 'CLIENT-001',
            'is_active' => true,
        ]);

        $this->actingAs($administrator)->post('/clients/contacts', [
            'client_id' => $client->id,
            'name' => 'Client Representative',
            'job_title' => 'Project Manager',
            'email' => 'representative@example.com',
            'phone' => null,
            'mobile' => null,
            'is_primary' => true,
        ])->assertRedirect();

        $this->assertDatabaseHas('client_contacts', [
            'client_id' => $client->id,
            'email' => 'representative@example.com',
        ]);
    }

    private function administrator(): User
    {
        $this->seed(UserAccessSeeder::class);
        $this->seed(ClientAccessSeeder::class);

        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'system-administrator')->firstOrFail();
        $user->roles()->sync([$role->id]);

        return $user;
    }
}
