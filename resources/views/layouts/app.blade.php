<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EMS') }} - @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&family=plus-jakarta-sans:600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="ems-body" x-data="appLayout()">

    <div class="ems-wrapper">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main content area --}}
        <div class="ems-main" :class="sidebarOpen ? 'ems-main--shifted' : ''">

            {{-- Navbar --}}
            @include('components.navbar')

            {{-- Page content --}}
            <main class="ems-content">
                @if (session('success'))
                    <div class="ems-alert ems-alert--success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="ems-alert__close">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="ems-alert ems-alert--error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <span>{{ session('error') }}</span>
                        <button @click="show = false" class="ems-alert__close">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Sidebar overlay for mobile --}}
    <div class="ems-overlay" x-show="sidebarOpen && isMobile()" @click="sidebarOpen = false" x-cloak></div>
    @stack('scripts')
</body>
</html>