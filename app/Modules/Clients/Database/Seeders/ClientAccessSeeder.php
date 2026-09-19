<?php

namespace App\Modules\Clients\Database\Seeders;

use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class ClientAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View clients', 'slug' => 'clients.view'],
            ['name' => 'Create clients', 'slug' => 'clients.create'],
            ['name' => 'Update clients', 'slug' => 'clients.update'],
            ['name' => 'Change client status', 'slug' => 'clients.change-status'],
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
