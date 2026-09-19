<?php

namespace App\Modules\Users\Database\Seeders;

use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class UserAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'View users', 'slug' => 'users.view'],
            ['name' => 'Create users', 'slug' => 'users.create'],
            ['name' => 'Update users', 'slug' => 'users.update'],
            ['name' => 'Change user status', 'slug' => 'users.change-status'],
            ['name' => 'View roles', 'slug' => 'roles.view'],
            ['name' => 'Create roles', 'slug' => 'roles.create'],
            ['name' => 'Update roles', 'slug' => 'roles.update'],
        ];

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(
                ['slug' => $permission['slug']],
                $permission,
            );
        }

        $administrator = Role::query()->updateOrCreate(
            ['slug' => 'system-administrator'],
            [
                'name' => 'System Administrator',
                'description' => 'Full system administration access.',
            ],
        );

        $administrator->permissions()->sync(
            Permission::query()->pluck('id'),
        );
    }
}
