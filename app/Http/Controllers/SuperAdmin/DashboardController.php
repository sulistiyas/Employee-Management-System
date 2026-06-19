<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index(): View
    {
        return view('super-admin.dash', [
            'stats' => $this->dashboardService->getStats(),
            'recentAttendances' => $this->dashboardService->getRecentAttendances(),
            'pendingLeaves' => $this->dashboardService->getPendingLeaves(),
            'departments' => $this->dashboardService->getDepartments(),
            'recentEmployees' => $this->dashboardService->getRecentEmployees(),
        ]);
    }
}