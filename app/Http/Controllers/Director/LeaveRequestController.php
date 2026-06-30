<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequests;
use App\Services\LeaveRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function __construct(private LeaveRequestService $leaveRequestService) {}

    public function index(Request $request): View
    {
        $leaveRequests = $this->leaveRequestService->getPendingForDirector($request->query('search'));

        if ($request->ajax()) {
            return view('director.leave-requests.table', [
                'leaveRequests' => $leaveRequests,
            ]);
        }

        return view('director.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
        ]);
    }

    public function approve(Request $request, LeaveRequests $leaveRequest): RedirectResponse
    {
        $this->leaveRequestService->approve($leaveRequest, $request->user()->employee, 'pending_director');

        return redirect()
            ->route('director.leave-requests.index')
            ->with('success', 'Pengajuan cuti disetujui (final).');
    }

    public function reject(Request $request, LeaveRequests $leaveRequest): RedirectResponse
    {
        $request->validate(['rejection_reason' => 'required|string|max:255']);

        $this->leaveRequestService->reject($leaveRequest, $request->input('rejection_reason'), 'pending_director');

        return redirect()
            ->route('director.leave-requests.index')
            ->with('success', 'Pengajuan cuti ditolak.');
    }
}
