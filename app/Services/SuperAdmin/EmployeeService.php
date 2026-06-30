<?php

namespace App\Services\SuperAdmin;

use App\Models\Employees;
use App\Repositories\SuperAdmin\EmployeeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    public function __construct(private EmployeeRepository $employeeRepository) {}

    public function getAllEmployees(
        ?string $search = null,
        ?int $departmentId = null,
        ?int $positionId = null,
        ?string $employmentStatus = null,
        ?string $sort = null,
        string $dir = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        return $this->employeeRepository->getAll(
            $search,
            $departmentId,
            $positionId,
            $employmentStatus,
            $sort,
            $dir,
            $perPage
        );
    }

    public function findEmployee(int $employeeId): ?Employees
    {
        return $this->employeeRepository->findById($employeeId);
    }

    /**
     * Ambil daftar employee yang belum memiliki akun user, untuk dropdown
     * pembuatan akun login pada modul User.
     *
     * @return Collection<int, Employees>
     */
    public function getAvailableEmployees(): Collection
    {
        return $this->employeeRepository->getAvailableForUser();
    }

    /**
     * Ambil daftar employee dengan status aktif, untuk dropdown penunjukan
     * manager atau HR penanggung jawab department.
     *
     * @return Collection<int, Employees>
     */
    public function getActiveEmployees(): Collection
    {
        return $this->employeeRepository->getActiveEmployees();
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

    /**
     * Hapus banyak employee sekaligus. Mengembalikan jumlah employee yang berhasil dihapus.
     */
    public function deleteManyEmployees(array $employeeIds): int
    {
        return $this->employeeRepository->deleteMany($employeeIds);
    }
}