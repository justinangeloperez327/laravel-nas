<?php

namespace App\Modules\Users\Actions;

use App\Modules\Users\Models\User;

class ChangeUserStatus
{
    public function execute(User $user, bool $isActive): User
    {
        $user->update([
            'is_active' => $isActive,
        ]);

        return $user->refresh();
    }
}
