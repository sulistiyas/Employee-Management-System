<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftRequest;
use App\Models\Shifts;
use App\Services\SuperAdmin\ShiftService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function __construct(private ShiftService $shiftService) {}

    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        $shifts  = $this->shiftService->getAllShifts($request->query('search'), $perPage);

        if ($request->ajax()) {
            return view('super-admin.shifts.table', ['shifts' => $shifts]);
        }

        return view('super-admin.shifts.index', [
            'shifts'     => $shifts,
            'shiftTypes' => Shifts::TYPES,
        ]);
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