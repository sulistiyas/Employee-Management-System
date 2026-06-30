<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Departments;
use App\Services\SuperAdmin\DepartmentService;
use App\Services\SuperAdmin\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function __construct(
        private DepartmentService $departmentService,
        private EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $departments = $this->departmentService->getAllDepartments(
            search: $request->query('search'),
            sort: $request->query('sort'),
            dir: $request->query('dir', 'asc'),
            perPage: (int) $request->query('per_page', 10)
        );

        if ($request->ajax()) {
            return view('super-admin.departments.table', [
                'departments' => $departments,
            ]);
        }

        $activeEmployees = $this->employeeService->getActiveEmployees();

        return view('super-admin.departments.index', [
            'departments' => $departments,
            'activeEmployees' => $activeEmployees,
        ]);
    }

    public function store(DepartmentRequest $request): RedirectResponse
    {
        $this->departmentService->createDepartment($request->validated());

        return redirect()
            ->route('super-admin.departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function update(DepartmentRequest $request, Departments $department): RedirectResponse
    {
        $this->departmentService->updateDepartment($department, $request->validated());

        return redirect()
            ->route('super-admin.departments.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Departments $department): RedirectResponse
    {
        $deleted = $this->departmentService->deleteDepartment($department);

        if (! $deleted) {
            return redirect()
                ->route('super-admin.departments.index')
                ->with('error', 'Departemen tidak dapat dihapus karena masih digunakan oleh employees.');
        }

        return redirect()
            ->route('super-admin.departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $departmentIds = $request->input('department_ids', []);
        $deletedCount = $this->departmentService->deleteManyDepartments($departmentIds);

        if ($deletedCount === 0) {
            return redirect()
                ->route('super-admin.departments.index')
                ->with('error', 'Departemen tidak dapat dihapus karena masih digunakan oleh employees.');
        }

        return redirect()
            ->route('super-admin.departments.index')
            ->with('success', "{$deletedCount} departemen berhasil dihapus.");
    }
}