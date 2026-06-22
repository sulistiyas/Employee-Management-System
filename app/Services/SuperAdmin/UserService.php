<?php

namespace App\Services\SuperAdmin;

use App\Models\User;
use App\Repositories\SuperAdmin\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(private UserRepository $userRepository) {}

    public function getAllUsers(
        ?string $search = null,
        ?int $roleId = null,
        ?string $isActive = null,
        ?string $sort = null,
        string $dir = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        return $this->userRepository->getAll(
            $search,
            $roleId,
            $isActive,
            $sort,
            $dir,
            $perPage
        );
    }

    public function findUser(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function updateUser(User $user, array $data): User
    {
        return $this->userRepository->update($user, $data);
    }

    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    /**
     * Hapus banyak user sekaligus. Mengembalikan jumlah user yang berhasil dihapus.
     */
    public function deleteManyUsers(array $userIds): int
    {
        return $this->userRepository->deleteMany($userIds);
    }
}
