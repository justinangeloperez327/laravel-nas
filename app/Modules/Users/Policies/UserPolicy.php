<?php

namespace App\Modules\Users\Policies;

use App\Modules\Users\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view');
    }

    public function view(User $user, User $target): bool
    {
        return $user->hasPermission('users.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user, User $target): bool
    {
        return $user->hasPermission('users.update');
    }

    public function changeStatus(User $user, User $target): bool
    {
        return ! $user->is($target)
            && $user->hasPermission('users.change-status');
    }
}
