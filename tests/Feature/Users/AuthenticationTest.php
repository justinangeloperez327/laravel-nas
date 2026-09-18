<?php

namespace Tests\Feature\Users;

use App\Modules\Users\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_active_user_can_authenticate(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_inactive_user_cannot_authenticate(): void
    {
        $user = User::factory()->create([
            'is_active' => false,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_unverified_user_is_sent_to_email_verification(): void
    {
        $user = User::factory()->unverified()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/verify-email');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_password_reset_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }
}
