<?php

namespace App\Services\SuperAdmin;

use App\Models\Employees;
use App\Repositories\SuperAdmin\EmployeeRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    public function __construct(private EmployeeRepository $employeeRepository) {}

    public function getAllEmployees(?string $search = null): LengthAwarePaginator
    {
        return $this->employeeRepository->getAll($search);
    }

    public function findEmployee(int $employeeId): ?Employees
    {
        return $this->employeeRepository->findById($employeeId);
    }

    public function createEmployee(array $data): Employees
    {
        return $this->employeeRepository->create($data);
    }

    public function updateEmployee(Employees $employee, array $data): Employees
    {
        return $this->employeeRepository->update($employee, $data);
    }

    public function deleteEmployee(Employees $employee): bool
    {
        if ($employee->user()->exists()) {
            return false;
        }

        return $this->employeeRepository->delete($employee);
    }
}
