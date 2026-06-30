<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Director\LeaveRequestController as DirectorLeaveRequestController;
use App\Http\Controllers\Hr\LeaveRequestController as HrLeaveRequestController;
use App\Http\Controllers\Manager\LeaveRequestController as ManagerLeaveRequestController;
use App\Http\Controllers\SuperAdmin\AttendanceController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DepartmentController;
use App\Http\Controllers\SuperAdmin\EmployeeController;
use App\Http\Controllers\SuperAdmin\LeaveRequestController;
use App\Http\Controllers\SuperAdmin\LeaveTypeController;
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

            Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
            Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
            Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');
            Route::delete('/attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
            Route::delete('/attendances-bulk', [AttendanceController::class, 'bulkDestroy'])->name('attendances.bulk-destroy');

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
            Route::get('/shifts/next-code', [ShiftController::class, 'getNextCode'])->name('shifts.next-code');
            Route::get('/shifts/available-employees', [ShiftController::class, 'availableEmployees'])->name('shifts.available-employees');
            Route::get('/shifts/{shift}', [ShiftController::class, 'show'])->name('shifts.show');
            Route::put('/shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
            Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
            Route::post('/shifts/{shift}/assign', [ShiftController::class, 'assign'])->name('shifts.assign');
            Route::delete('/shifts/{shift}/assignments', [ShiftController::class, 'removeAssignments'])->name('shifts.remove-assignments');

            Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index');
            Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
            Route::put('/leave-types/{leave_type}', [LeaveTypeController::class, 'update'])->name('leave-types.update');
            Route::delete('/leave-types/{leave_type}', [LeaveTypeController::class, 'destroy'])->name('leave-types.destroy');
            Route::delete('/leave-types-bulk', [LeaveTypeController::class, 'bulkDestroy'])->name('leave-types.bulk-destroy');

            // Leave requests: read-only monitor. Approve/reject dipindah ke Manager/HR/Director.
            Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');

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

    // ── Manager ──
    Route::middleware('role:manager')
        ->prefix('manager')
        ->name('manager.')
        ->group(function () {
            Route::get('/leave-requests', [ManagerLeaveRequestController::class, 'index'])->name('leave-requests.index');
            Route::put('/leave-requests/{leaveRequest}/approve', [ManagerLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::put('/leave-requests/{leaveRequest}/reject', [ManagerLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        });

    // ── HR ──
    Route::middleware('role:hr')
        ->prefix('hr')
        ->name('hr.')
        ->group(function () {
            Route::get('/leave-requests', [HrLeaveRequestController::class, 'index'])->name('leave-requests.index');
            Route::put('/leave-requests/{leaveRequest}/approve', [HrLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::put('/leave-requests/{leaveRequest}/reject', [HrLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        });

    // ── Director ──
    Route::middleware('role:director')
        ->prefix('director')
        ->name('director.')
        ->group(function () {
            Route::get('/leave-requests', [DirectorLeaveRequestController::class, 'index'])->name('leave-requests.index');
            Route::put('/leave-requests/{leaveRequest}/approve', [DirectorLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::put('/leave-requests/{leaveRequest}/reject', [DirectorLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        });
});