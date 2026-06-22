<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Employees;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository
{
    /**
     * Kolom yang boleh dipakai untuk sorting beserta mapping kolom aktualnya.
     */
    private const SORTABLE_COLUMNS = [
        'employee_number' => 'employees.employee_number',
        'full_name' => 'employees.full_name',
        'department' => 'departments.name',
        'position' => 'positions.name',
        'employment_status' => 'employees.employment_status',
    ];

    public function getAll(
        ?string $search = null,
        ?int $departmentId = null,
        ?int $positionId = null,
        ?string $employmentStatus = null,
        ?string $sort = null,
        string $dir = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Employees::with('department', 'position')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($departmentId, function ($query, $departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->when($positionId, function ($query, $positionId) {
                $query->where('position_id', $positionId);
            })
            ->when($employmentStatus, function ($query, $employmentStatus) {
                $query->where('employment_status', $employmentStatus);
            });

        if ($sort && array_key_exists($sort, self::SORTABLE_COLUMNS)) {
            $dir = $dir === 'desc' ? 'desc' : 'asc';

            if (in_array($sort, ['department', 'position'])) {
                $query->leftJoin('departments', 'departments.department_id', '=', 'employees.department_id')
                    ->leftJoin('positions', 'positions.position_id', '=', 'employees.position_id')
                    ->select('employees.*');
            }

            $query->orderBy(self::SORTABLE_COLUMNS[$sort], $dir);
        } else {
            $query->orderBy('full_name');
        }

        return $query->paginate($perPage)->withQueryString();
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

    /**
     * Hapus banyak employee sekaligus berdasarkan ID.
     * Employee yang masih memiliki user account dilewati (tidak ikut dihapus).
     */
    public function deleteMany(array $employeeIds): int
    {
        return Employees::whereIn('employee_id', $employeeIds)
            ->whereDoesntHave('user')
            ->get()
            ->each(fn (Employees $employee) => $employee->delete())
            ->count();
    }
}