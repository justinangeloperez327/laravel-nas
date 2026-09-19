<?php

namespace App\Modules\Users;

use App\Modules\Users\Actions\ResetUserPassword;
use App\Modules\Users\Console\CreateAdministratorCommand;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use App\Modules\Users\Policies\RolePolicy;
use App\Modules\Users\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class UsersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasPermission($ability) ? true : null;
        });

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateAdministratorCommand::class,
            ]);
        }

        Password::defaults(
            fn (): Password => Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols(),
        );

        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(fn (Request $request) => Inertia::render('auth/login', [
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
            'status' => $request->session()->get('status'),
        ]));

        Fortify::requestPasswordResetLinkView(
            fn (Request $request) => Inertia::render('auth/forgot-password', [
                'status' => $request->session()->get('status'),
            ]),
        );

        Fortify::resetPasswordView(
            fn (Request $request) => Inertia::render('auth/reset-password', [
                'email' => $request->email,
                'token' => $request->route('token'),
                'passwordRules' => Password::defaults()->toPasswordRulesString(),
            ]),
        );

        Fortify::verifyEmailView(
            fn (Request $request) => Inertia::render('auth/verify-email', [
                'status' => $request->session()->get('status'),
            ]),
        );

        Fortify::authenticateUsing(function (Request $request): ?User {
            $user = User::query()
                ->where('email', strtolower((string) $request->input('email')))
                ->first();

            if (! $user || ! $user->is_active || ! Hash::check((string) $request->input('password'), $user->password)) {
                return null;
            }

            $user->forceFill([
                'last_login_at' => now(),
            ])->save();

            return $user;
        });

        RateLimiter::for('login', function (Request $request): Limit {
            $key = Str::transliterate(
                Str::lower((string) $request->input(Fortify::username())).'|'.$request->ip(),
            );

            return Limit::perMinute(5)->by($key);
        });
    }
}
