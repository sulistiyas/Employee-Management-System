<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Director\LeaveRequestController as DirectorLeaveRequestController;
use App\Http\Controllers\Hr\LeaveRequestController as HrLeaveRequestController;
use App\Http\Controllers\Manager\LeaveRequestController as ManagerLeaveRequestController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DepartmentController;
use App\Http\Controllers\SuperAdmin\EmployeeController;
use App\Http\Controllers\SuperAdmin\PositionController;
use App\Http\Controllers\SuperAdmin\RoleController;
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
    // Tambahkan controller yang sesuai saat modul dashboard role lain dikembangkan.
    Route::get('/dashboard/staff', fn () => 'Staff Dashboard')->name('dashboard.staff');

    // ── Manager ──
    Route::middleware('role:manager')
        ->prefix('manager')
        ->name('manager.')
        ->group(function () {
            Route::get('/dashboard', fn () => 'Manager Dashboard')->name('dashboard');

            Route::get('/leave-requests', [ManagerLeaveRequestController::class, 'index'])->name('leave-requests.index');
            Route::post('/leave-requests/{leaveRequest}/approve', [ManagerLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::post('/leave-requests/{leaveRequest}/reject', [ManagerLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        });

    // ── HR ──
    Route::middleware('role:hr')
        ->prefix('hr')
        ->name('hr.')
        ->group(function () {
            Route::get('/dashboard', fn () => 'HR Dashboard')->name('dashboard');

            Route::get('/leave-requests', [HrLeaveRequestController::class, 'index'])->name('leave-requests.index');
            Route::post('/leave-requests/{leaveRequest}/approve', [HrLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::post('/leave-requests/{leaveRequest}/reject', [HrLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        });

    // ── Director ──
    Route::middleware('role:director')
        ->prefix('director')
        ->name('director.')
        ->group(function () {
            Route::get('/dashboard', fn () => 'Director Dashboard')->name('dashboard');

            Route::get('/leave-requests', [DirectorLeaveRequestController::class, 'index'])->name('leave-requests.index');
            Route::post('/leave-requests/{leaveRequest}/approve', [DirectorLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::post('/leave-requests/{leaveRequest}/reject', [DirectorLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        });

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

            Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
            Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
            Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
            Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');

            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
            Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
            Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
            Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
            // Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
            // Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        });
});
