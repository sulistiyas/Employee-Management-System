<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequests;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function __construct(private LeaveRequestService $leaveRequestService) {}

    public function index(Request $request): View
    {
        $leaveRequests = $this->leaveRequestService->getAllForMonitor(
            $request->query('search'),
            $request->query('status'),
        );

        if ($request->ajax()) {
            return view('super-admin.leave-requests.table', [
                'leaveRequests' => $leaveRequests,
            ]);
        }

        return view('super-admin.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'statuses' => LeaveRequests::STATUSES,
        ]);
    }
}