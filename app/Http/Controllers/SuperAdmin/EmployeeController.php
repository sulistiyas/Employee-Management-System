<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Departments;
use App\Models\Employees;
use App\Models\Positions;
use App\Services\SuperAdmin\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $employeeService) {}

    public function index(Request $request): View
    {
        $employees = $this->employeeService->getAllEmployees(
            search: $request->query('search'),
            departmentId: $request->query('department') ? (int) $request->query('department') : null,
            positionId: $request->query('position') ? (int) $request->query('position') : null,
            employmentStatus: $request->query('status'),
            sort: $request->query('sort'),
            dir: $request->query('dir', 'asc'),
            perPage: (int) $request->query('per_page', 10)
        );
        $departments = Departments::orderBy('name')->get();
        $positions = Positions::orderBy('name')->get();

        if ($request->ajax()) {
            return view('super-admin.employees.table', [
                'employees' => $employees,
            ]);
        }

        return view('super-admin.employees.index', [
            'employees' => $employees,
            'departments' => $departments,
            'positions' => $positions,
        ]);
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $this->employeeService->createEmployee($request->validated());

        return redirect()
            ->route('super-admin.employees.index')
            ->with('success', 'Employee berhasil ditambahkan.');
    }

    public function update(EmployeeRequest $request, Employees $employee): RedirectResponse
    {
        $this->employeeService->updateEmployee($employee, $request->validated());

        return redirect()
            ->route('super-admin.employees.index')
            ->with('success', 'Employee berhasil diperbarui.');
    }

    public function destroy(Employees $employee): RedirectResponse
    {
        $deleted = $this->employeeService->deleteEmployee($employee);

        if (! $deleted) {
            return redirect()
                ->route('super-admin.employees.index')
                ->with('error', 'Employee tidak dapat dihapus karena memiliki akun user.');
        }

        return redirect()
            ->route('super-admin.employees.index')
            ->with('success', 'Employee berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $employeeIds = $request->input('employee_ids', []);
        $deletedCount = $this->employeeService->deleteManyEmployees($employeeIds);

        if ($deletedCount === 0) {
            return redirect()
                ->route('super-admin.employees.index')
                ->with('error', 'Employee tidak dapat dihapus karena memiliki akun user.');
        }

        return redirect()
            ->route('super-admin.employees.index')
            ->with('success', "{$deletedCount} employee berhasil dihapus.");
    }
}