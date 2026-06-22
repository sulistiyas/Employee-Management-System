<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Employees;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository
{
    public function getAll(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Employees::with('department', 'position')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('full_name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $employeeId): ?Employees
    {
        return Employees::with('department', 'position')->find($employeeId);
    }

    public function create(array $data): Employees
    {
        return Employees::create($data);
    }

    public function update(Employees $employee, array $data): Employees
    {
        $employee->update($data);

        return $employee;
    }

    public function delete(Employees $employee): bool
    {
        return $employee->delete();
    }
}
