<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendances;
use App\Services\SuperAdmin\AttendanceService;
use App\Services\SuperAdmin\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private AttendanceService $attendanceService,
        private EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        $attendances = $this->attendanceService->getAllAttendances(
            $request->query('search'),
            $request->query('status'),
            $request->query('date'),
            $perPage
        );

        if ($request->ajax()) {
            return view('super-admin.attendances.table', [
                'attendances' => $attendances,
            ]);
        }

        return view('super-admin.attendances.index', [
            'attendances' => $attendances,
            'employees' => $this->employeeService->getActiveEmployees(),
            'statuses' => Attendances::STATUSES,
        ]);
    }

    public function store(AttendanceRequest $request): RedirectResponse
    {
        $this->attendanceService->createAttendance($request->validated());

        return redirect()
            ->route('super-admin.attendances.index')
            ->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function update(AttendanceRequest $request, Attendances $attendance): RedirectResponse
    {
        $this->attendanceService->updateAttendance($attendance, $request->validated());

        return redirect()
            ->route('super-admin.attendances.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(Attendances $attendance): RedirectResponse
    {
        $this->attendanceService->deleteAttendance($attendance);

        return redirect()
            ->route('super-admin.attendances.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $attendanceIds = $request->input('attendance_ids', []);
        $deletedCount = $this->attendanceService->deleteManyAttendances($attendanceIds);

        return redirect()
            ->route('super-admin.attendances.index')
            ->with('success', "{$deletedCount} data absensi berhasil dihapus.");
    }
}
