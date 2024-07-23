<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return User::create($data);
    }

    /**
     * @param array $credentials
     * @return string|null
     */
    public function login(array $credentials): ?string
    {
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            return $user->createToken('authToken')->plainTextToken;
        }

        return null;
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}
