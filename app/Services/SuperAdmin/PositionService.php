<?php

namespace App\Services\SuperAdmin;

use App\Models\Positions;
use App\Repositories\SuperAdmin\PositionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class PositionService
{
    public function __construct(private PositionRepository $positionRepository) {}

    public function getAllPositions(?string $search = null): LengthAwarePaginator
    {
        return $this->positionRepository->getAll($search);
    }

    public function findPosition(int $positionId): ?Positions
    {
        return $this->positionRepository->findById($positionId);
    }

    public function createPosition(array $data): Positions
    {
        return $this->positionRepository->create($data);
    }

    public function updatePosition(Positions $position, array $data): Positions
    {
        return $this->positionRepository->update($position, $data);
    }

    /**
     * Hapus position. Position yang masih digunakan oleh employees tidak boleh dihapus.
     */
    public function deletePosition(Positions $position): bool
    {
        if ($position->employees()->exists()) {
            return false;
        }

        return $this->positionRepository->delete($position);
    }

    /**
     * Hapus banyak position sekaligus. Mengembalikan jumlah position yang berhasil dihapus.
     */
    public function deleteManyPositions(array $positionIds): int
    {
        return $this->positionRepository->deleteMany($positionIds);
    }
}
