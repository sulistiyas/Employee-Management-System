<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(private AuthRepository $authRepository) {}

    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        $user = $this->authRepository->findByEmail($credentials['email']);

        if (! $user) {
            return false;
        }

        return Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        );
    }

    public function logout(): void
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Menentukan route redirect berdasarkan role slug user.
     * Tambahkan route name sesuai kebutuhan modul yang dikembangkan.
     */
    public function getRedirectRouteName(User $user): string
    {
        $slug = $user->role?->slug ?? 'staff';

        return match ($slug) {
            'super-admin' => 'dashboard.super-admin',
            'director'    => 'dashboard.director',
            'manager'     => 'dashboard.manager',
            'hr'          => 'dashboard.hr',
            default       => 'dashboard.staff',
        };
    }
}