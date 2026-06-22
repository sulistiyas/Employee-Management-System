<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DepartmentController;
use App\Http\Controllers\SuperAdmin\EmployeeController;
use App\Http\Controllers\SuperAdmin\PositionController;
use App\Http\Controllers\SuperAdmin\RoleController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
// Route::get('/', fn () => redirect()->route('login'))->name('home');

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
            // Route::resource('employees', EmployeeController::class);
            // Route::resource('departments', DepartmentController::class);
            // Route::resource('positions', PositionController::class);
            // Route::resource('attendances', AttendanceController::class);
            // Route::resource('shifts', ShiftController::class);
            // Route::resource('holidays', HolidayController::class);
            // Route::resource('leave-requests', LeaveRequestController::class);
            // Route::patch('leave-requests/{leave_request}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            // Route::patch('leave-requests/{leave_request}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
            // Route::resource('leave-types', LeaveTypeController::class);
            // Route::resource('users', UserController::class);
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
