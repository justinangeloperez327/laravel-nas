<?php

namespace App\Modules\Organization\Database\Seeders;

use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class OrganizationAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View organization', 'slug' => 'organization.view'],
            ['name' => 'Create organization records', 'slug' => 'organization.create'],
            ['name' => 'Update organization records', 'slug' => 'organization.update'],
            ['name' => 'Change organization record status', 'slug' => 'organization.change-status'],
        ])->map(function (array $permission): Permission {
            return Permission::query()->updateOrCreate(
                ['slug' => $permission['slug']],
                $permission,
            );
        });

        $administrator = Role::query()
            ->where('slug', 'system-administrator')
            ->first();

        if ($administrator) {
            $administrator->permissions()->syncWithoutDetaching(
                $permissions->pluck('id'),
            );
        }
    }
}
