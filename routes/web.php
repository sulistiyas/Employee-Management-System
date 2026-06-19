<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ─── Authenticated Routes ─────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Dashboard per Role ──
    // Tambahkan controller yang sesuai saat modul dashboard dikembangkan.
    // Contoh: Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.staff');

    Route::get('/dashboard/super-admin', fn () => 'Super Admin Dashboard')->name('dashboard.super-admin');
    Route::get('/dashboard/director',    fn () => 'Director Dashboard')->name('dashboard.director');
    Route::get('/dashboard/manager',     fn () => 'Manager Dashboard')->name('dashboard.manager');
    Route::get('/dashboard/hr',          fn () => 'HR Dashboard')->name('dashboard.hr');
    Route::get('/dashboard/staff',       fn () => 'Staff Dashboard')->name('dashboard.staff');
});