<?php

namespace App\Modules\Approvals\Database\Seeders;

use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Database\Seeder;

class ApprovalAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'View approvals', 'slug' => 'approvals.view'],
            ['name' => 'Configure approval workflows', 'slug' => 'approvals.configure'],
            ['name' => 'Act on approval requests', 'slug' => 'approvals.act'],
        ])->map(fn (array $permission): Permission => Permission::query()->updateOrCreate(['slug' => $permission['slug']], $permission));

        $administrator = Role::query()->where('slug', 'system-administrator')->first();
        if ($administrator) $administrator->permissions()->syncWithoutDetaching($permissions->pluck('id'));
    }
}
