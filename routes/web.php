<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\RoleController;
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
    Route::get('/dashboard/manager',  fn () => 'Manager Dashboard')->name('dashboard.manager');
    Route::get('/dashboard/hr',       fn () => 'HR Dashboard')->name('dashboard.hr');
    Route::get('/dashboard/staff',    fn () => 'Staff Dashboard')->name('dashboard.staff');

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
            // Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
            // Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        });
});