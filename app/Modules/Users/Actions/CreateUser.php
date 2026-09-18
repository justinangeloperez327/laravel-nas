<?php

namespace App\Modules\Users\Actions;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;

class CreateUser
{
    /**
     * @param array{name:string,email:string,password:string,is_active:bool,role_ids:array<int,int>} $data
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'is_active' => $data['is_active'],
            ]);

            $user->roles()->sync($data['role_ids']);
            $user->sendEmailVerificationNotification();

            return $user->load('roles');
        });
    }
}
