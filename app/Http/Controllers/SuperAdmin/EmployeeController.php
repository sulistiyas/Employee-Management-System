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
        $employees = $this->employeeService->getAllEmployees($request->query('search'));
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
}
