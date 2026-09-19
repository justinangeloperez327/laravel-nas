<?php

namespace App\Modules\Suppliers\Database\Seeders;

use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class SupplierAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View suppliers', 'slug' => 'suppliers.view'],
            ['name' => 'Create suppliers', 'slug' => 'suppliers.create'],
            ['name' => 'Update suppliers', 'slug' => 'suppliers.update'],
            ['name' => 'Approve suppliers', 'slug' => 'suppliers.approve'],
            ['name' => 'View supplier banking data', 'slug' => 'suppliers.view-sensitive'],
        ])->map(fn (array $permission): Permission => Permission::query()->updateOrCreate(['slug' => $permission['slug']], $permission));

        $administrator = Role::query()->where('slug', 'system-administrator')->first();
        if ($administrator) $administrator->permissions()->syncWithoutDetaching($permissions->pluck('id'));
    }
}
