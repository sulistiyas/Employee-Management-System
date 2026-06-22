<?php

namespace App\Repositories\SuperAdmin;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * Kolom yang boleh dipakai untuk sorting beserta mapping kolom aktualnya.
     */
    private const SORTABLE_COLUMNS = [
        'name' => 'users.name',
        'email' => 'users.email',
        'role' => 'roles.name',
        'is_active' => 'users.is_active',
    ];

    public function getAll(
        ?string $search = null,
        ?int $roleId = null,
        ?string $isActive = null,
        ?string $sort = null,
        string $dir = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = User::with('role', 'employee')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleId, function ($query, $roleId) {
                $query->where('role_id', $roleId);
            })
            ->when($isActive !== null, function ($query) use ($isActive) {
                $query->where('is_active', (bool) $isActive);
            });

        if ($sort && array_key_exists($sort, self::SORTABLE_COLUMNS)) {
            $dir = $dir === 'desc' ? 'desc' : 'asc';

            if ($sort === 'role') {
                $query->leftJoin('roles', 'roles.role_id', '=', 'users.role_id')
                    ->select('users.*');
            }

            $query->orderBy(self::SORTABLE_COLUMNS[$sort], $dir);
        } else {
            $query->orderBy('name');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $userId): ?User
    {
        return User::with('role', 'employee')->find($userId);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Hapus banyak user sekaligus berdasarkan ID.
     */
    public function deleteMany(array $userIds): int
    {
        return User::whereIn('id', $userIds)
            ->get()
            ->each(fn (User $user) => $user->delete())
            ->count();
    }
}
