<?php

namespace App\Modules\Contracts\Database\Seeders;

use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class ContractAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View contracts', 'slug' => 'contracts.view'],
            ['name' => 'Create contracts', 'slug' => 'contracts.create'],
            ['name' => 'Update contracts', 'slug' => 'contracts.update'],
            ['name' => 'Manage contract amendments', 'slug' => 'contracts.manage-amendments'],
        ])->map(fn (array $permission): Permission => Permission::query()->updateOrCreate(['slug' => $permission['slug']], $permission));

        $administrator = Role::query()->where('slug', 'system-administrator')->first();
        if ($administrator) $administrator->permissions()->syncWithoutDetaching($permissions->pluck('id'));
    }
}
