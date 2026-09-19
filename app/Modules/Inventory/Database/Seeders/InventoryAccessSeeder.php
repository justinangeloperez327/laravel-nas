<?php

namespace App\Modules\Inventory\Database\Seeders;

use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class InventoryAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View inventory', 'slug' => 'inventory.view'],
            ['name' => 'Manage inventory master data', 'slug' => 'inventory.manage'],
            ['name' => 'Record stock movements', 'slug' => 'inventory.move'],
            ['name' => 'Adjust stock', 'slug' => 'inventory.adjust'],
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
