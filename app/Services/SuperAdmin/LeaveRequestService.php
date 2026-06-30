<?php

namespace App\Services\SuperAdmin;

use App\Models\LeaveRequests;
use App\Repositories\SuperAdmin\LeaveRequestRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class LeaveRequestService
{
    public function __construct(private LeaveRequestRepository $leaveRequestRepository) {}

    public function getAllLeaveRequests(?string $search = null, ?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->leaveRequestRepository->getAll($search, $status, $perPage);
    }

    public function findLeaveRequest(int $leaveRequestId): ?LeaveRequests
    {
        return $this->leaveRequestRepository->findById($leaveRequestId);
    }

    public function createLeaveRequest(array $data): LeaveRequests
    {
        $data['total_days'] = $this->calculateTotalDays($data['start_date'], $data['end_date']);
        $data['status'] = 'pending';

        return $this->leaveRequestRepository->create($data);
    }

    public function updateLeaveRequest(LeaveRequests $leaveRequest, array $data): LeaveRequests
    {
        $data['total_days'] = $this->calculateTotalDays($data['start_date'], $data['end_date']);

        return $this->leaveRequestRepository->update($leaveRequest, $data);
    }

    /**
     * Hapus leave request. Leave request yang sudah disetujui tidak boleh dihapus.
     */
    public function deleteLeaveRequest(LeaveRequests $leaveRequest): bool
    {
        if ($leaveRequest->status === 'approved') {
            return false;
        }

        return $this->leaveRequestRepository->delete($leaveRequest);
    }

    /**
     * Hapus banyak leave request sekaligus. Mengembalikan jumlah yang berhasil dihapus.
     */
    public function deleteManyLeaveRequests(array $leaveRequestIds): int
    {
        return $this->leaveRequestRepository->deleteMany($leaveRequestIds);
    }

    public function approveLeaveRequest(LeaveRequests $leaveRequest, int $approvedBy): LeaveRequests
    {
        return $this->leaveRequestRepository->update($leaveRequest, [
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
        ]);
    }

    public function rejectLeaveRequest(LeaveRequests $leaveRequest, int $approvedBy): LeaveRequests
    {
        return $this->leaveRequestRepository->update($leaveRequest, [
            'status' => 'rejected',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
        ]);
    }

    private function calculateTotalDays(string $startDate, string $endDate): int
    {
        return Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
    }
}
