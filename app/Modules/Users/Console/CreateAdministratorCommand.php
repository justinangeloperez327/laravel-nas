<?php

namespace App\Modules\Users\Console;

use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdministratorCommand extends Command
{
    protected $signature = 'users:create-administrator
        {email : Administrator email address}
        {--name=System Administrator : Administrator display name}';

    protected $description = 'Create the initial system administrator account';

    public function handle(UserAccessSeeder $seeder): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        $name = trim((string) $this->option('name'));

        if (User::query()->where('email', $email)->exists()) {
            $this->error('A user with this email address already exists.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Password');
        $confirmation = (string) $this->secret('Confirm password');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $confirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', Password::default(), 'confirmed'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $seeder->run();

        $role = Role::query()
            ->where('slug', 'system-administrator')
            ->firstOrFail();

        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'is_active' => true,
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $user->roles()->sync([$role->id]);

        $this->info("Administrator {$user->email} created successfully.");

        return self::SUCCESS;
    }
}
