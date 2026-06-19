<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Roles;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository
{
    public function getAll(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Roles::withCount('users')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
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