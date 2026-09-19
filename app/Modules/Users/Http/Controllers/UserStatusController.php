<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Actions\ChangeUserStatus;
use App\Modules\Users\Http\Requests\ChangeUserStatusRequest;
use App\Modules\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class UserStatusController extends Controller
{
    public function update(
        ChangeUserStatusRequest $request,
        User $user,
        ChangeUserStatus $changeUserStatus,
    ): RedirectResponse {
        Gate::authorize('changeStatus', $user);

        $changeUserStatus->execute(
            $user,
            $request->boolean('is_active'),
        );

        return back()->with(
            'success',
            $request->boolean('is_active')
                ? 'User activated successfully.'
                : 'User deactivated successfully.',
        );
    }
}
