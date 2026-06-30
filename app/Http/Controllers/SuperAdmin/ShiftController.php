<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeShiftAssignRequest;
use App\Http\Requests\EmployeeShiftRemoveRequest;
use App\Http\Requests\ShiftRequest;
use App\Models\Shifts;
use App\Services\SuperAdmin\EmployeeShiftService;
use App\Services\SuperAdmin\ShiftService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function __construct(
        private ShiftService $shiftService,
        private EmployeeShiftService $employeeShiftService
    ) {}

    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        $shifts  = $this->shiftService->getAllShifts(
            search: $request->query('search'),
            sort: $request->query('sort'),
            dir: $request->query('dir', 'asc'),
            perPage: $perPage
        );

        if ($request->ajax()) {
            return view('super-admin.shifts.table', ['shifts' => $shifts]);
        }

        return view('super-admin.shifts.index', [
            'shifts'     => $shifts,
            'shiftTypes' => Shifts::TYPES,
        ]);
    }

    /**
     * Halaman detail shift: daftar karyawan yang sedang aktif di shift ini,
     * plus akses ke assign/copot karyawan.
     */
    public function show(Request $request, Shifts $shift): View
    {
        $perPage = (int) $request->query('per_page', 10);
        $assignments = $this->employeeShiftService->getActiveByShift(
            $shift->shift_id,
            $request->query('search'),
            $request->query('sort'),
            $request->query('dir', 'asc'),
            $perPage
        );

        if ($request->ajax()) {
            return view('super-admin.shifts.assignment-table', [
                'shift' => $shift,
                'assignments' => $assignments,
            ]);
        }

        return view('super-admin.shifts.show', [
            'shift' => $shift,
            'assignments' => $assignments,
        ]);
    }

    /**
     * Daftar karyawan yang belum punya shift aktif (untuk modal assign), via AJAX.
     */
    public function availableEmployees(Request $request): JsonResponse
    {
        $employees = $this->employeeShiftService->getAvailableEmployees($request->query('search'));

        return response()->json([
            'employees' => $employees->map(fn ($employee) => [
                'employee_id' => $employee->employee_id,
                'employee_number' => $employee->employee_number,
                'full_name' => $employee->full_name,
                'department' => $employee->department?->name,
                'position' => $employee->position?->name,
            ]),
        ]);
    }

    public function assign(EmployeeShiftAssignRequest $request, Shifts $shift): RedirectResponse
    {
        try {
            $count = $this->employeeShiftService->bulkAssign(
                employeeIds: $request->validated('employee_ids'),
                shiftId: $shift->shift_id,
                effectiveDate: Carbon::parse($request->validated('effective_date')),
                changedBy: auth()->id()
            );
        } catch (\DomainException $e) {
            return redirect()
                ->route('super-admin.shifts.show', $shift)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('super-admin.shifts.show', $shift)
            ->with('success', "{$count} karyawan berhasil di-assign ke shift ini.");
    }

    public function removeAssignments(EmployeeShiftRemoveRequest $request, Shifts $shift): RedirectResponse
    {
        $count = $this->employeeShiftService->bulkRemove(
            $request->validated('employee_ids'),
            $shift->shift_id
        );

        if ($count === 0) {
            return redirect()
                ->route('super-admin.shifts.show', $shift)
                ->with('error', 'Tidak ada assignment yang dihapus.');
        }

        return redirect()
            ->route('super-admin.shifts.show', $shift)
            ->with('success', "{$count} karyawan berhasil dicopot dari shift ini.");
    }

    public function getNextCode(Request $request): JsonResponse
    {
        $type = $request->query('type');

        if (!$type || !array_key_exists($type, Shifts::TYPES)) {
            return response()->json(['code' => ''], 422);
        }

        return response()->json([
            'code' => $this->shiftService->getNextCode($type),
        ]);
    }

    public function store(ShiftRequest $request): RedirectResponse
    {
        $this->shiftService->createShift($request->validated());

        return redirect()
            ->route('super-admin.shifts.index')
            ->with('success', 'Shift berhasil ditambahkan.');
    }

    public function update(ShiftRequest $request, Shifts $shift): RedirectResponse
    {
        $this->shiftService->updateShift($shift, $request->validated());

        return redirect()
            ->route('super-admin.shifts.index')
            ->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy(Shifts $shift): RedirectResponse
    {
        $deleted = $this->shiftService->deleteShift($shift);

        if (!$deleted) {
            return redirect()
                ->route('super-admin.shifts.index')
                ->with('error', 'Shift tidak dapat dihapus karena masih digunakan oleh karyawan.');
        }

        return redirect()
            ->route('super-admin.shifts.index')
            ->with('success', 'Shift berhasil dihapus.');
    }
}