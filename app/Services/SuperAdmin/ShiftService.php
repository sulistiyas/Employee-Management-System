<?php

namespace App\Services\SuperAdmin;

use App\Models\Shifts;
use App\Repositories\SuperAdmin\ShiftRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ShiftService
{
    public function __construct(private ShiftRepository $shiftRepository) {}

    public function getAllShifts(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->shiftRepository->getAll($search, $perPage);
    }

    public function findShift(int $shiftId): ?Shifts
    {
        return $this->shiftRepository->findById($shiftId);
    }

    public function createShift(array $data): Shifts
    {
        // Generate kode otomatis dari jenis shift
        $data['code'] = $this->shiftRepository->getNextCodeForType($data['name']);

        return $this->shiftRepository->create($data);
    }

    public function updateShift(Shifts $shift, array $data): Shifts
    {
        // Jika jenis shift berubah, generate kode baru
        if ($shift->name !== $data['name']) {
            $data['code'] = $this->shiftRepository->getNextCodeForType($data['name']);
        }

        return $this->shiftRepository->update($shift, $data);
    }

    public function deleteShift(Shifts $shift): bool
    {
        if ($shift->employeeShifts()->exists()) {
            return false;
        }

        return $this->shiftRepository->delete($shift);
    }

    // Untuk endpoint AJAX — ambil preview kode
    public function getNextCode(string $type): string
    {
        return $this->shiftRepository->getNextCodeForType($type);
    }
}