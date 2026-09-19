<?php

namespace Tests\Feature\Users;

use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_create_role_with_permissions(): void
    {
        $administrator = $this->administrator();

        $permission = Permission::query()
            ->where('slug', 'users.view')
            ->firstOrFail();

        $response = $this->actingAs($administrator)
            ->post('/administration/roles', [
                'name' => 'User Viewer',
                'slug' => 'user-viewer',
                'description' => 'Can view application users.',
                'permission_ids' => [$permission->id],
            ]);

        $response->assertRedirect('/administration/roles');

        $role = Role::query()
            ->where('slug', 'user-viewer')
            ->firstOrFail();

        $this->assertTrue(
            $role->permissions()->whereKey($permission->id)->exists(),
        );
    }

    public function test_system_administrator_role_cannot_be_modified(): void
    {
        $administrator = $this->administrator();

        $role = Role::query()
            ->where('slug', 'system-administrator')
            ->firstOrFail();

        $this->actingAs($administrator)
            ->put("/administration/roles/{$role->id}", [
                'name' => 'Changed',
                'slug' => 'changed',
                'description' => null,
                'permission_ids' => [],
            ])
            ->assertForbidden();

        $this->assertSame(
            'system-administrator',
            $role->fresh()->slug,
        );
    }

    public function test_user_without_permission_cannot_view_roles(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/administration/roles')
            ->assertForbidden();
    }

    private function administrator(): User
    {
        $this->seed(UserAccessSeeder::class);

        $administrator = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $role = Role::query()
            ->where('slug', 'system-administrator')
            ->firstOrFail();

        $administrator->roles()->sync([$role->id]);

        return $administrator;
    }
}
