<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftRequest;
use App\Models\Shifts;
use App\Services\SuperAdmin\ShiftService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function __construct(private ShiftService $shiftService) {}

    public function index(Request $request): View
    {
        $shifts = $this->shiftService->getAllShifts($request->query('search'));

        if ($request->ajax()) {
            return view('super-admin.shifts.table', [
                'shifts' => $shifts,
            ]);
        }

        return view('super-admin.shifts.index', [
            'shifts' => $shifts,
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

        if (! $deleted) {
            return redirect()
                ->route('super-admin.shifts.index')
                ->with('error', 'Shift tidak dapat dihapus karena masih digunakan oleh employee.');
        }

        return redirect()
            ->route('super-admin.shifts.index')
            ->with('success', 'Shift berhasil dihapus.');
    }
}
