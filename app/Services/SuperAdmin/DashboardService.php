<?php

namespace App\Services\SuperAdmin;

use App\Repositories\SuperAdmin\DashboardRepository;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function __construct(private DashboardRepository $dashboardRepository) {}

    public function getStats(): array
    {
        $totalEmployees = $this->dashboardRepository->countTotalEmployees();
        $presentToday = $this->dashboardRepository->countPresentToday();

        return [
            'total_employees' => $totalEmployees,
            'new_employees_this_month' => $this->dashboardRepository->countNewEmployeesThisMonth(),
            'present_today' => $presentToday,
            'late_today' => $this->dashboardRepository->countLateToday(),
            'on_leave_today' => $this->dashboardRepository->countOnLeaveToday(),
            'absent_today' => $this->dashboardRepository->countAbsentToday(),
            'attendance_rate' => $totalEmployees > 0
                ? round(($presentToday / $totalEmployees) * 100)
                : 0,
            'pending_leave' => $this->dashboardRepository->countPendingLeaveRequests(),
            'total_departments' => $this->dashboardRepository->countTotalDepartments(),
            'total_positions' => $this->dashboardRepository->countTotalPositions(),
        ];
    }

    public function getRecentAttendances(): Collection
    {
        return $this->dashboardRepository->getRecentAttendancesToday();
    }

    public function getPendingLeaves(): Collection
    {
        return $this->dashboardRepository->getPendingLeaveRequests();
    }

    public function getDepartments(): Collection
    {
        return $this->dashboardRepository->getDepartmentsWithEmployeeCount();
    }

    public function getRecentEmployees(): Collection
    {
        return $this->dashboardRepository->getRecentEmployees();
    }
}