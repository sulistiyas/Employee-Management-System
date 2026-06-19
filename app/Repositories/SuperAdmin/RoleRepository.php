<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Roles;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    public function getAll(): Collection
    {
        return Roles::withCount('users')
            ->orderBy('name')
            ->get();
    }

    public function findById(int $roleId): ?Roles
    {
        return Roles::find($roleId);
    }

    public function create(array $data): Roles
    {
        return Roles::create($data);
    }

    public function update(Roles $role, array $data): Roles
    {
        $role->update($data);

        return $role;
    }

    public function delete(Roles $role): bool
    {
        return $role->delete();
    }
}