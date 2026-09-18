<?php

namespace App\Modules\Users\Actions;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateUser
{
    /**
     * @param  array{name:string,email:string,password?:string|null,role_ids:array<int,int>}  $data
     */
    public function execute(User $actor, User $user, array $data): User
    {
        if ($actor->is($user) && $this->rolesChanged($user, $data['role_ids'])) {
            throw ValidationException::withMessages([
                'role_ids' => 'You cannot change your own roles.',
            ]);
        }

        $emailChanged = $user->email !== $data['email'];

        DB::transaction(function () use ($user, $data, $emailChanged): void {
            $attributes = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if ($data['password'] !== null && $data['password'] !== '') {
                $attributes['password'] = $data['password'];
            }

            $user->update($attributes);

            if ($emailChanged) {
                $user->forceFill([
                    'email_verified_at' => null,
                ])->save();
            }

            $user->roles()->sync($data['role_ids']);
        });

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
        }

        return $user->refresh()->load('roles');
    }

    /**
     * @param  array<int,int>  $roleIds
     */
    private function rolesChanged(User $user, array $roleIds): bool
    {
        $current = $user->roles()->pluck('roles.id')->sort()->values()->all();
        $incoming = collect($roleIds)->sort()->values()->all();

        return $current !== $incoming;
    }
}
