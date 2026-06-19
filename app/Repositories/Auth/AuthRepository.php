<?php

namespace App\Repositories\Auth;

use App\Models\User;

class AuthRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::with('role')
            ->where('email', $email)
            ->first();
    }
}