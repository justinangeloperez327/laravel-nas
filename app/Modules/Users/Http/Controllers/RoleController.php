<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Actions\CreateRole;
use App\Modules\Users\Actions\UpdateRole;
use App\Modules\Users\Http\Requests\StoreRoleRequest;
use App\Modules\Users\Http\Requests\UpdateRoleRequest;
use App\Modules\Users\Models\Permission;
use App\Modules\Users\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', Role::class);

        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->orderBy('name')
            ->get()
            ->map(fn (Role $role): array => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'description' => $role->description,
                'users_count' => $role->users_count,
                'permissions_count' => $role->permissions_count,
                'can_edit' => Gate::allows('update', $role),
            ]);

        return Inertia::render('roles/index', [
            'roles' => $roles,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Role::class);

        return Inertia::render('roles/create', [
            'permissions' => $this->permissions(),
        ]);
    }

    public function store(StoreRoleRequest $request, CreateRole $createRole): RedirectResponse
    {
        Gate::authorize('create', Role::class);

        $createRole->execute($request->validated());

        return to_route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): Response
    {
        Gate::authorize('update', $role);

        $role->load('permissions:id,name,slug');

        return Inertia::render('roles/edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'description' => $role->description,
                'permission_ids' => $role->permissions->pluck('id')->values(),
            ],
            'permissions' => $this->permissions(),
        ]);
    }

    public function update(
        UpdateRoleRequest $request,
        Role $role,
        UpdateRole $updateRole,
    ): RedirectResponse {
        Gate::authorize('update', $role);

        $updateRole->execute($role, $request->validated());

        return to_route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * @return array<int,array{id:int,name:string,slug:string,description:?string}>
     */
    private function permissions(): array
    {
        return Permission::query()
            ->orderBy('slug')
            ->get(['id', 'name', 'slug', 'description'])
            ->map(fn (Permission $permission): array => [
                'id' => $permission->id,
                'name' => $permission->name,
                'slug' => $permission->slug,
                'description' => $permission->description,
            ])
            ->all();
    }
}
