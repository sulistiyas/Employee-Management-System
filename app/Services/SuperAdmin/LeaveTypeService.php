<?php

namespace App\Services\SuperAdmin;

use App\Models\LeaveTypes;
use App\Repositories\SuperAdmin\LeaveTypeRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaveTypeService
{
    public function __construct(private LeaveTypeRepository $leaveTypeRepository) {}

    public function getAllLeaveTypes(
        ?string $search = null,
        ?string $sort = null,
        string $dir = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        return $this->leaveTypeRepository->getAll($search, $sort, $dir, $perPage);
    }

    public function findLeaveType(int $leaveTypeId): ?LeaveTypes
    {
        return $this->leaveTypeRepository->findById($leaveTypeId);
    }

    public function createLeaveType(array $data): LeaveTypes
    {
        return $this->leaveTypeRepository->create($data);
    }

    public function updateLeaveType(LeaveTypes $leaveType, array $data): LeaveTypes
    {
        return $this->leaveTypeRepository->update($leaveType, $data);
    }

    /**
     * Hapus leave type. Leave type yang masih digunakan oleh leave requests tidak boleh dihapus.
     */
    public function deleteLeaveType(LeaveTypes $leaveType): bool
    {
        if ($leaveType->leaveRequests()->exists()) {
            return false;
        }

        return $this->leaveTypeRepository->delete($leaveType);
    }

    /**
     * Hapus banyak leave type sekaligus. Mengembalikan jumlah leave type yang berhasil dihapus.
     */
    public function deleteManyLeaveTypes(array $leaveTypeIds): int
    {
        return $this->leaveTypeRepository->deleteMany($leaveTypeIds);
    }
}