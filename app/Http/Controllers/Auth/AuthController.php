<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route($this->authService->getRedirectRouteName(Auth::user()));
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $result = $this->authService->attemptLogin(
            $request->validated(),
            $request->boolean('remember')
        );

        if (! $result) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(
            route($this->authService->getRedirectRouteName(Auth::user()))
        );
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login');
    }
}