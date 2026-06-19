<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login &mdash; {{ config('app.name', 'Employee Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">

    <!-- Vite: login.css + app.js (Alpine terpusat) -->
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">

        {{-- Brand --}}
        <div class="login-brand">
            <div class="login-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 110-8 4 4 0 010 8zm6 4a2 2 0 11-4 0 2 2 0 014 0zM5 16a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h1 class="login-brand-title">{{ config('app.name', 'Employee Management') }}</h1>
            <p class="login-brand-subtitle">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        {{-- Error global (bukan field-specific) --}}
        @if (session('error'))
            <div class="login-alert" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Login --}}
        <form action="{{ route('login') }}" method="POST" class="login-form" id="loginForm"
              x-data="loginForm()" @submit="handleSubmit">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="nama@perusahaan.com"
                        autocomplete="email"
                        autofocus
                        required
                    >
                </div>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        class="form-control has-toggle @error('password') is-invalid @enderror"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                    <button
                        type="button"
                        class="btn-toggle-password"
                        @click="showPassword = !showPassword"
                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                    >
                        {{-- Eye icon --}}
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{-- Eye-off icon --}}
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                             style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.592M9.88 9.88A3 3 0 0112 9a3 3 0 013 3m0 0a3 3 0 01-3 3m-3-3a3 3 0 013-3M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remember me + Forgot --}}
            <div class="form-row-between">
                <label class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input"
                           {{ old('remember') ? 'checked' : '' }}>
                    <span class="form-check-label">Ingat saya</span>
                </label>
                {{-- Uncomment jika fitur forgot password sudah ada --}}
                {{-- <a href="{{ route('password.request') }}" class="link-forgot">Lupa password?</a> --}}
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="btn-login"
                :class="{ loading: isSubmitting }"
                :disabled="isSubmitting"
            >
                <span class="spinner"></span>
                <span class="btn-text">Masuk</span>
            </button>
        </form>

        {{-- Footer --}}
        <p class="login-footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Employee Management') }}. All rights reserved.
        </p>

    </div>
</div>

</body>
</html>