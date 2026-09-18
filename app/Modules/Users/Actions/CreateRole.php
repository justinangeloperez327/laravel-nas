<?php

namespace App\Modules\Users\Actions;

use App\Modules\Users\Models\Role;
use Illuminate\Support\Facades\DB;

class CreateRole
{
    /**
     * @param array{name:string,slug:string,description?:string|null,permission_ids:array<int,int>} $data
     */
    public function execute(array $data): Role
    {
        return DB::transaction(function () use ($data): Role {
            $role = Role::query()->create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
            ]);

            $role->permissions()->sync($data['permission_ids']);

            return $role->load('permissions');
        });
    }
}
