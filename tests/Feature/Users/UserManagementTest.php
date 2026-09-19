<?php

namespace Tests\Feature\Users;

use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_permission_cannot_view_user_administration(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/administration/users')
            ->assertForbidden();
    }

    public function test_administrator_can_view_user_administration(): void
    {
        $administrator = $this->administrator();

        $this->actingAs($administrator)
            ->get('/administration/users')
            ->assertOk();
    }

    public function test_administrator_can_create_user_with_role(): void
    {
        Notification::fake();

        $administrator = $this->administrator();

        $role = Role::query()->create([
            'name' => 'Project User',
            'slug' => 'project-user',
        ]);

        $response = $this->actingAs($administrator)
            ->post('/administration/users', [
                'name' => 'Project User',
                'email' => 'project.user@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'is_active' => true,
                'role_ids' => [$role->id],
            ]);

        $response->assertRedirect('/administration/users');

        $user = User::query()
            ->where('email', 'project.user@example.com')
            ->firstOrFail();

        $this->assertTrue($user->is_active);
        $this->assertTrue($user->roles()->whereKey($role->id)->exists());

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_administrator_cannot_change_own_roles(): void
    {
        $administrator = $this->administrator();

        $otherRole = Role::query()->create([
            'name' => 'Other Role',
            'slug' => 'other-role',
        ]);

        $this->actingAs($administrator)
            ->from("/administration/users/{$administrator->id}/edit")
            ->put("/administration/users/{$administrator->id}", [
                'name' => $administrator->name,
                'email' => $administrator->email,
                'password' => null,
                'password_confirmation' => null,
                'role_ids' => [$otherRole->id],
            ])
            ->assertSessionHasErrors('role_ids');

        $this->assertTrue(
            $administrator->fresh()
                ->roles()
                ->where('slug', 'system-administrator')
                ->exists(),
        );
    }

    public function test_administrator_cannot_deactivate_own_account(): void
    {
        $administrator = $this->administrator();

        $this->actingAs($administrator)
            ->patch("/administration/users/{$administrator->id}/status", [
                'is_active' => false,
            ])
            ->assertForbidden();

        $this->assertTrue($administrator->fresh()->is_active);
    }

    public function test_administrator_can_deactivate_another_user(): void
    {
        $administrator = $this->administrator();
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($administrator)
            ->patch("/administration/users/{$user->id}/status", [
                'is_active' => false,
            ])
            ->assertRedirect();

        $this->assertFalse($user->fresh()->is_active);
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
