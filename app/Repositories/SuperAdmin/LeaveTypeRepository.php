<?php

namespace App\Repositories\SuperAdmin;

use App\Models\LeaveTypes;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaveTypeRepository
{
    public function getAll(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return LeaveTypes::withCount('leaveRequests')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $leaveTypeId): ?LeaveTypes
    {
        return LeaveTypes::find($leaveTypeId);
    }

    public function create(array $data): LeaveTypes
    {
        return LeaveTypes::create($data);
    }

    public function update(LeaveTypes $leaveType, array $data): LeaveTypes
    {
        $leaveType->update($data);

        return $leaveType;
    }

    public function delete(LeaveTypes $leaveType): bool
    {
        return $leaveType->delete();
    }

    /**
     * Hapus banyak leave type sekaligus berdasarkan ID.
     * Leave type yang masih memiliki leave requests dilewati (tidak ikut dihapus).
     */
    public function deleteMany(array $leaveTypeIds): int
    {
        return LeaveTypes::whereIn('leave_type_id', $leaveTypeIds)
            ->whereDoesntHave('leaveRequests')
            ->get()
            ->each(fn (LeaveTypes $leaveType) => $leaveType->delete())
            ->count();
    }
}