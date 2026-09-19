<?php

namespace App\Modules\Projects\Database\Seeders;

use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class ProjectAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View projects', 'slug' => 'projects.view'],
            ['name' => 'Create projects', 'slug' => 'projects.create'],
            ['name' => 'Update projects', 'slug' => 'projects.update'],
            ['name' => 'Manage project progress', 'slug' => 'projects.manage-progress'],
        ])->map(fn (array $permission): Permission => Permission::query()->updateOrCreate(
            ['slug' => $permission['slug']],
            $permission,
        ));

        $administrator = Role::query()->where('slug', 'system-administrator')->first();

        if ($administrator) {
            $administrator->permissions()->syncWithoutDetaching($permissions->pluck('id'));
        }
    }
}
