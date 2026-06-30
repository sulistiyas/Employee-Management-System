<?php

namespace App\Repositories\SuperAdmin;

use App\Models\LeaveRequests;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaveRequestRepository
{
    public function getAll(?string $search = null, ?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return LeaveRequests::with(['employee', 'leaveType', 'approvedBy'])
            ->when($search, function ($query, $search) {
                $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderByDesc('start_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $leaveRequestId): ?LeaveRequests
    {
        return LeaveRequests::find($leaveRequestId);
    }

    public function create(array $data): LeaveRequests
    {
        return LeaveRequests::create($data);
    }

    public function update(LeaveRequests $leaveRequest, array $data): LeaveRequests
    {
        $leaveRequest->update($data);

        return $leaveRequest;
    }

    public function delete(LeaveRequests $leaveRequest): bool
    {
        return $leaveRequest->delete();
    }

    /**
     * Hapus banyak leave request sekaligus berdasarkan ID.
     * Leave request yang sudah disetujui (approved) tidak ikut dihapus.
     */
    public function deleteMany(array $leaveRequestIds): int
    {
        return LeaveRequests::whereIn('leave_request_id', $leaveRequestIds)
            ->where('status', '!=', 'approved')
            ->get()
            ->each(fn (LeaveRequests $leaveRequest) => $leaveRequest->delete())
            ->count();
    }
}
