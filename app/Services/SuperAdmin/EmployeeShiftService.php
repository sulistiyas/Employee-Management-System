<?php

namespace App\Services\SuperAdmin;

use App\Models\EmployeeShifts;
use App\Repositories\SuperAdmin\EmployeeShiftRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class EmployeeShiftService
{
    public function __construct(private EmployeeShiftRepository $employeeShiftRepository) {}

    public function getActiveByShift(int $shiftId, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->employeeShiftRepository->getActiveByShift($shiftId, $search, $perPage);
    }

    /**
     * @return Collection<int, \App\Models\Employees>
     */
    public function getAvailableEmployees(?string $search = null): Collection
    {
        return $this->employeeShiftRepository->getAvailableEmployees($search);
    }

    /**
     * Assign banyak karyawan ke satu shift dengan satu effective_date yang sama.
     *
     * Business rule: hanya karyawan yang BELUM punya shift aktif yang boleh
     * di-assign. Validasi ini dilakukan ulang di sini (bukan hanya mengandalkan
     * filter "available" di UI) untuk mencegah race condition — misalnya dua
     * admin membuka modal yang sama secara bersamaan.
     *
     * @param  int[]  $employeeIds
     * @return int Jumlah karyawan yang berhasil di-assign.
     *
     * @throws \DomainException Jika salah satu karyawan sudah punya shift aktif.
     */
    public function bulkAssign(array $employeeIds, int $shiftId, Carbon $effectiveDate, ?int $changedBy): int
    {
        $alreadyAssigned = EmployeeShifts::whereIn('employee_id', $employeeIds)
            ->where('effective_date', '<=', now()->toDateString())
            ->pluck('employee_id')
            ->unique();

        if ($alreadyAssigned->isNotEmpty()) {
            throw new \DomainException(
                'Beberapa karyawan yang dipilih sudah memiliki shift aktif. Muat ulang halaman dan coba lagi.'
            );
        }

        return $this->employeeShiftRepository->bulkAssign($employeeIds, $shiftId, $effectiveDate, $changedBy);
    }

    /**
     * Copot assignment aktif sejumlah karyawan dari shift tertentu.
     *
     * @param  int[]  $employeeIds
     */
    public function bulkRemove(array $employeeIds, int $shiftId): int
    {
        return $this->employeeShiftRepository->removeActiveAssignments($employeeIds, $shiftId);
    }

    /**
     * @return Collection<int, EmployeeShifts>
     */
    public function getHistoryByEmployee(int $employeeId): Collection
    {
        return $this->employeeShiftRepository->getHistoryByEmployee($employeeId);
    }
}
