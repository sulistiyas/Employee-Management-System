<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Departments;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentRepository
{
    public function getAll(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Departments::withCount('employees')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $departmentId): ?Departments
    {
        return Departments::find($departmentId);
    }

    public function create(array $data): Departments
    {
        return Departments::create($data);
    }

    public function update(Departments $department, array $data): Departments
    {
        $department->update($data);

        return $department;
    }

    public function delete(Departments $department): bool
    {
        return $department->delete();
    }
}
