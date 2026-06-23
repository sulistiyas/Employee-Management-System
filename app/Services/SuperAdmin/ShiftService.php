<?php

namespace App\Services\SuperAdmin;

use App\Models\Shifts;
use App\Repositories\SuperAdmin\ShiftRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ShiftService
{
    public function __construct(private ShiftRepository $shiftRepository) {}

    public function getAllShifts(?string $search = null): LengthAwarePaginator
    {
        return $this->shiftRepository->getAll($search);
    }

    public function findShift(int $shiftId): ?Shifts
    {
        return $this->shiftRepository->findById($shiftId);
    }

    public function createShift(array $data): Shifts
    {
        return $this->shiftRepository->create($data);
    }

    public function updateShift(Shifts $shift, array $data): Shifts
    {
        return $this->shiftRepository->update($shift, $data);
    }

    /**
     * Hapus shift. Shift yang masih digunakan oleh employee shifts tidak boleh dihapus.
     */
    public function deleteShift(Shifts $shift): bool
    {
        if ($shift->employeeShifts()->exists()) {
            return false;
        }

        return $this->shiftRepository->delete($shift);
    }
}
