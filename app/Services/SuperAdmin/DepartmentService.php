<?php

namespace App\Services\SuperAdmin;

use App\Models\Departments;
use App\Repositories\SuperAdmin\DepartmentRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentService
{
    public function __construct(private DepartmentRepository $departmentRepository) {}

    public function getAllDepartments(?string $search = null): LengthAwarePaginator
    {
        return $this->departmentRepository->getAll($search);
    }

    public function findDepartment(int $departmentId): ?Departments
    {
        return $this->departmentRepository->findById($departmentId);
    }

    public function createDepartment(array $data): Departments
    {
        return $this->departmentRepository->create($data);
    }

    public function updateDepartment(Departments $department, array $data): Departments
    {
        return $this->departmentRepository->update($department, $data);
    }

    /**
     * Hapus department. Department yang masih digunakan oleh employees tidak boleh dihapus.
     */
    public function deleteDepartment(Departments $department): bool
    {
        if ($department->employees()->exists()) {
            return false;
        }

        return $this->departmentRepository->delete($department);
    }
}
