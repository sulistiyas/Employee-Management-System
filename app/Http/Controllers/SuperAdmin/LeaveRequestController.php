<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequestRequest;
use App\Models\LeaveRequests;
use App\Models\LeaveTypes;
use App\Services\SuperAdmin\EmployeeService;
use App\Services\SuperAdmin\LeaveRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function __construct(
        private LeaveRequestService $leaveRequestService,
        private EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        $leaveRequests = $this->leaveRequestService->getAllLeaveRequests(
            $request->query('search'),
            $request->query('status'),
            $perPage
        );

        if ($request->ajax()) {
            return view('super-admin.leave-requests.table', [
                'leaveRequests' => $leaveRequests,
            ]);
        }

        return view('super-admin.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'employees' => $this->employeeService->getActiveEmployees(),
            'leaveTypes' => LeaveTypes::orderBy('name')->get(),
            'statuses' => LeaveRequests::STATUSES,
        ]);
    }

    public function store(LeaveRequestRequest $request): RedirectResponse
    {
        $this->leaveRequestService->createLeaveRequest($request->validated());

        return redirect()
            ->route('super-admin.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil ditambahkan.');
    }

    public function update(LeaveRequestRequest $request, LeaveRequests $leave_request): RedirectResponse
    {
        $this->leaveRequestService->updateLeaveRequest($leave_request, $request->validated());

        return redirect()
            ->route('super-admin.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    public function destroy(LeaveRequests $leave_request): RedirectResponse
    {
        $deleted = $this->leaveRequestService->deleteLeaveRequest($leave_request);

        if (! $deleted) {
            return redirect()
                ->route('super-admin.leave-requests.index')
                ->with('error', 'Pengajuan cuti yang sudah disetujui tidak dapat dihapus.');
        }

        return redirect()
            ->route('super-admin.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $leaveRequestIds = $request->input('leave_request_ids', []);
        $deletedCount = $this->leaveRequestService->deleteManyLeaveRequests($leaveRequestIds);

        if ($deletedCount === 0) {
            return redirect()
                ->route('super-admin.leave-requests.index')
                ->with('error', 'Pengajuan cuti yang sudah disetujui tidak dapat dihapus.');
        }

        return redirect()
            ->route('super-admin.leave-requests.index')
            ->with('success', "{$deletedCount} pengajuan cuti berhasil dihapus.");
    }

    public function approve(LeaveRequests $leave_request): RedirectResponse
    {
        $this->leaveRequestService->approveLeaveRequest($leave_request, auth()->user()->employee_id);

        return redirect()
            ->route('super-admin.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    public function reject(LeaveRequests $leave_request): RedirectResponse
    {
        $this->leaveRequestService->rejectLeaveRequest($leave_request, auth()->user()->employee_id);

        return redirect()
            ->route('super-admin.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil ditolak.');
    }
}
