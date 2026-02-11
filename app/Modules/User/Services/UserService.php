<?php

namespace App\Modules\User\Services;

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function index()
    {
        return User::all();
    }

    public function updateProfile($user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function updatePassword($user, array $data)
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json(['error' => 'Password atual incorreta'], 422);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return ['message' => 'Password atualizada com sucesso'];
    }
}
