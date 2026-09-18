<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Actions\CreateUser;
use App\Modules\Users\Actions\UpdateUser;
use App\Modules\Users\Http\Requests\StoreUserRequest;
use App\Modules\Users\Http\Requests\UpdateUserRequest;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', User::class);

        $search = trim((string) $request->string('search'));

        $users = User::query()
            ->with('roles:id,name,slug')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at?->toISOString(),
                'is_active' => $user->is_active,
                'last_login_at' => $user->last_login_at?->toISOString(),
                'roles' => $user->roles->map(fn (Role $role): array => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'slug' => $role->slug,
                ])->values(),
            ]);

        return Inertia::render('users/index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
            ],
            'permissions' => [
                'create' => $request->user()?->can('create', User::class) ?? false,
                'update' => $request->user()?->can('update', new User) ?? false,
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('users/create', [
            'roles' => $this->roles(),
        ]);
    }

    public function store(StoreUserRequest $request, CreateUser $createUser): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $createUser->execute($request->validated());

        return to_route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): Response
    {
        Gate::authorize('update', $user);

        $user->load('roles:id,name,slug');

        return Inertia::render('users/edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'role_ids' => $user->roles->pluck('id')->values(),
            ],
            'roles' => $this->roles(),
        ]);
    }

    public function update(
        UpdateUserRequest $request,
        User $user,
        UpdateUser $updateUser,
    ): RedirectResponse {
        Gate::authorize('update', $user);

        /** @var User $actor */
        $actor = $request->user();

        $updateUser->execute($actor, $user, $request->validated());

        return to_route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * @return array<int,array{id:int,name:string,slug:string}>
     */
    private function roles(): array
    {
        return Role::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug'])
            ->map(fn (Role $role): array => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ])
            ->all();
    }
}
