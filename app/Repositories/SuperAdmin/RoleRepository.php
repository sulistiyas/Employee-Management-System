<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Roles;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository
{
    /**
     * Kolom yang boleh dipakai untuk sorting beserta mapping kolom aktualnya.
     */
    private const SORTABLE_COLUMNS = [
        'name' => 'name',
        'slug' => 'slug',
    ];

    public function getAll(
        ?string $search = null,
        ?string $sort = null,
        string $dir = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Roles::withCount('users')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });

        if ($sort && array_key_exists($sort, self::SORTABLE_COLUMNS)) {
            $dir = $dir === 'desc' ? 'desc' : 'asc';
            $query->orderBy(self::SORTABLE_COLUMNS[$sort], $dir);
        } else {
            $query->orderBy('name');
        }

        return $query->paginate($perPage)->withQueryString();
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

    /**
     * Hapus banyak role sekaligus berdasarkan ID.
     * Role yang masih memiliki user account dilewati (tidak ikut dihapus).
     */
    public function deleteMany(array $roleIds): int
    {
        return Roles::whereIn('role_id', $roleIds)
            ->withCount('users')
            ->get()
            ->filter(fn (Roles $role) => $role->users_count === 0)
            ->each(fn (Roles $role) => $role->delete())
            ->count();
    }
}