<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Attendances;
use App\Models\Departments;
use App\Models\Employees;
use App\Models\LeaveRequests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class DashboardRepository
{
    public function countTotalEmployees(): int
    {
        return Employees::count();
    }

    public function countNewEmployeesThisMonth(): int
    {
        return Employees::whereYear('join_date', now()->year)
            ->whereMonth('join_date', now()->month)
            ->count();
    }

    public function countPresentToday(): int
    {
        return Attendances::whereDate('attendance_date', today())
            ->where('attendance_status', 'present')
            ->count();
    }

    public function countLateToday(): int
    {
        return Attendances::whereDate('attendance_date', today())
            ->where('attendance_status', 'late')
            ->count();
    }

    public function countOnLeaveToday(): int
    {
        return Attendances::whereDate('attendance_date', today())
            ->where('attendance_status', 'permit')
            ->count();
    }

    public function countAbsentToday(): int
    {
        return Attendances::whereDate('attendance_date', today())
            ->where('attendance_status', 'absent')
            ->count();
    }

    public function countPendingLeaveRequests(): int
    {
        return LeaveRequests::where('status', 'pending')->count();
    }

    public function countTotalDepartments(): int
    {
        return Departments::count();
    }

    public function countTotalPositions(): int
    {
        return Departments::withCount('positions')->get()->sum('positions_count');
    }

    public function getRecentAttendancesToday(int $limit = 5): Collection
    {
        return Attendances::with(['employee.department'])
            ->whereDate('attendance_date', today())
            ->latest('check_in')
            ->limit($limit)
            ->get();
    }

    public function getPendingLeaveRequests(int $limit = 5): Collection
    {
        return LeaveRequests::with(['employee', 'leaveType'])
            ->where('status', 'pending')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getDepartmentsWithEmployeeCount(): Collection
    {
        return Departments::withCount('employees')->get();
    }

    public function getRecentEmployees(int $limit = 5): Collection
    {
        return Employees::with(['position', 'department'])
            ->latest('join_date')
            ->limit($limit)
            ->get();
    }
}