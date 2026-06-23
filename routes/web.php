<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DepartmentController;
use App\Http\Controllers\SuperAdmin\EmployeeController;
use App\Http\Controllers\SuperAdmin\PositionController;
use App\Http\Controllers\SuperAdmin\RoleController;
use App\Http\Controllers\SuperAdmin\ShiftController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', fn () => redirect()->route('login'))->name('home');

// ─── Public Routes ────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ─── Authenticated Routes ─────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Dashboard per Role ──
    Route::get('/dashboard/director', fn () => 'Director Dashboard')->name('dashboard.director');
    Route::get('/dashboard/manager', fn () => 'Manager Dashboard')->name('dashboard.manager');
    Route::get('/dashboard/hr', fn () => 'HR Dashboard')->name('dashboard.hr');
    Route::get('/dashboard/staff', fn () => 'Staff Dashboard')->name('dashboard.staff');

    // ── Super Admin ──
    Route::middleware('role:super-admin')
        ->prefix('super-admin')
        ->name('super-admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            //
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

            Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
            Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
            Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
            Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
            Route::delete('/departments-bulk', [DepartmentController::class, 'bulkDestroy'])->name('departments.bulk-destroy');

            Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
            Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
            Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
            Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
            Route::delete('/positions-bulk', [PositionController::class, 'bulkDestroy'])->name('positions.bulk-destroy');

            Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
            Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
            Route::put('/shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
            Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');

            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::delete('/users-bulk', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');

            Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
            Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
            Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
            Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
            Route::delete('/employees-bulk', [EmployeeController::class, 'bulkDestroy'])->name('employees.bulk-destroy');
            // Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
            // Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        });
});
