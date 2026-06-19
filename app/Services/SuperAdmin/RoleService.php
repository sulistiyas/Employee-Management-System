<?php

namespace App\Services\SuperAdmin;

use App\Models\Roles;
use App\Repositories\SuperAdmin\RoleRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleService
{
    public function __construct(private RoleRepository $roleRepository) {}

    public function getAllRoles(?string $search = null): LengthAwarePaginator
    {
        return $this->roleRepository->getAll($search);
    }

    public function findRole(int $roleId): ?Roles
    {
        return $this->roleRepository->findById($roleId);
    }

    public function createRole(array $data): Roles
    {
        return $this->roleRepository->create($data);
    }

    public function updateRole(Roles $role, array $data): Roles
    {
        return $this->roleRepository->update($role, $data);
    }

    /**
     * Hapus role. Role yang masih digunakan oleh user tidak boleh dihapus.
     */
    public function deleteRole(Roles $role): bool
    {
        if ($role->users()->exists()) {
            return false;
        }

        return $this->roleRepository->delete($role);
    }
}