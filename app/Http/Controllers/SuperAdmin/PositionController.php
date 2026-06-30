<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use App\Models\Departments;
use App\Models\Positions;
use App\Services\SuperAdmin\PositionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionController extends Controller
{
    public function __construct(private PositionService $positionService) {}

    public function index(Request $request): View
    {
        $positions = $this->positionService->getAllPositions(
            search: $request->query('search'),
            sort: $request->query('sort'),
            dir: $request->query('dir', 'asc'),
            perPage: (int) $request->query('per_page', 10)
        );
        $departments = Departments::orderBy('name')->get();

        if ($request->ajax()) {
            return view('super-admin.positions.table', [
                'positions' => $positions,
            ]);
        }

        return view('super-admin.positions.index', [
            'positions' => $positions,
            'departments' => $departments,
        ]);
    }

    public function store(PositionRequest $request): RedirectResponse
    {
        $this->positionService->createPosition($request->validated());

        return redirect()
            ->route('super-admin.positions.index')
            ->with('success', 'Posisi berhasil ditambahkan.');
    }

    public function update(PositionRequest $request, Positions $position): RedirectResponse
    {
        $this->positionService->updatePosition($position, $request->validated());

        return redirect()
            ->route('super-admin.positions.index')
            ->with('success', 'Posisi berhasil diperbarui.');
    }

    public function destroy(Positions $position): RedirectResponse
    {
        $deleted = $this->positionService->deletePosition($position);

        if (! $deleted) {
            return redirect()
                ->route('super-admin.positions.index')
                ->with('error', 'Posisi tidak dapat dihapus karena masih digunakan oleh employees.');
        }

        return redirect()
            ->route('super-admin.positions.index')
            ->with('success', 'Posisi berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $positionIds = $request->input('position_ids', []);
        $deletedCount = $this->positionService->deleteManyPositions($positionIds);

        if ($deletedCount === 0) {
            return redirect()
                ->route('super-admin.positions.index')
                ->with('error', 'Posisi tidak dapat dihapus karena masih digunakan oleh employees.');
        }

        return redirect()
            ->route('super-admin.positions.index')
            ->with('success', "{$deletedCount} posisi berhasil dihapus.");
    }
}