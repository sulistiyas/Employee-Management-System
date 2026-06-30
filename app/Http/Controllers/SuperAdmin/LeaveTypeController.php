<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveTypeRequest;
use App\Models\LeaveTypes;
use App\Services\SuperAdmin\LeaveTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveTypeController extends Controller
{
    public function __construct(private LeaveTypeService $leaveTypeService) {}

    public function index(Request $request): View
    {
        $leaveTypes = $this->leaveTypeService->getAllLeaveTypes($request->query('search'));

        if ($request->ajax()) {
            return view('super-admin.leave-types.table', [
                'leaveTypes' => $leaveTypes,
            ]);
        }

        return view('super-admin.leave-types.index', [
            'leaveTypes' => $leaveTypes,
        ]);
    }

    public function store(LeaveTypeRequest $request): RedirectResponse
    {
        $this->leaveTypeService->createLeaveType($request->validated());

        return redirect()
            ->route('super-admin.leave-types.index')
            ->with('success', 'Jenis cuti berhasil ditambahkan.');
    }

    public function update(LeaveTypeRequest $request, LeaveTypes $leave_type): RedirectResponse
    {
        $this->leaveTypeService->updateLeaveType($leave_type, $request->validated());

        return redirect()
            ->route('super-admin.leave-types.index')
            ->with('success', 'Jenis cuti berhasil diperbarui.');
    }

    public function destroy(LeaveTypes $leave_type): RedirectResponse
    {
        $deleted = $this->leaveTypeService->deleteLeaveType($leave_type);

        if (! $deleted) {
            return redirect()
                ->route('super-admin.leave-types.index')
                ->with('error', 'Jenis cuti tidak dapat dihapus karena masih digunakan oleh leave requests.');
        }

        return redirect()
            ->route('super-admin.leave-types.index')
            ->with('success', 'Jenis cuti berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $leaveTypeIds = $request->input('leave_type_ids', []);
        $deletedCount = $this->leaveTypeService->deleteManyLeaveTypes($leaveTypeIds);

        if ($deletedCount === 0) {
            return redirect()
                ->route('super-admin.leave-types.index')
                ->with('error', 'Jenis cuti tidak dapat dihapus karena masih digunakan oleh leave requests.');
        }

        return redirect()
            ->route('super-admin.leave-types.index')
            ->with('success', "{$deletedCount} jenis cuti berhasil dihapus.");
    }
}