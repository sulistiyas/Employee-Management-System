<?php

namespace App\Services;

use App\Models\Employees;
use App\Models\LeaveRequests;
use App\Repositories\LeaveRequestRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaveRequestService
{
    public function __construct(private LeaveRequestRepository $leaveRequestRepository) {}

    /**
     * Buat pengajuan cuti baru. Jika pengaju adalah manager departemen,
     * level approval manager dilewati otomatis (langsung pending_hr).
     */
    public function createLeaveRequest(array $data): LeaveRequests
    {
        $employee = Employees::findOrFail($data['employee_id']);

        $data['status'] = $this->isDepartmentManager($employee)
            ? 'pending_hr'
            : 'pending_manager';

        return $this->leaveRequestRepository->create($data);
    }

    public function getPendingForManager(int $managerEmployeeId, ?string $search = null): LengthAwarePaginator
    {
        return $this->leaveRequestRepository->getAllByStatus('pending_manager', $search, $managerEmployeeId);
    }

    public function getPendingForHr(?string $search = null): LengthAwarePaginator
    {
        return $this->leaveRequestRepository->getAllByStatus('pending_hr', $search);
    }

    public function getPendingForDirector(?string $search = null): LengthAwarePaginator
    {
        return $this->leaveRequestRepository->getAllByStatus('pending_director', $search);
    }

    /**
     * Approve pengajuan cuti. $expectedStatus dipakai sebagai guard supaya
     * approval hanya bisa dilakukan kalau pengajuan memang sedang di tahap
     * yang sesuai dengan role yang mengakses (ditentukan oleh controller pemanggil).
     */
    public function approve(LeaveRequests $leaveRequest, Employees $approver, string $expectedStatus): LeaveRequests
    {
        if ($leaveRequest->status !== $expectedStatus) {
            throw new \RuntimeException('Pengajuan cuti tidak berada pada tahap yang sesuai untuk disetujui.');
        }

        return match ($expectedStatus) {
            'pending_manager' => $this->leaveRequestRepository->update($leaveRequest, [
                'manager_approved_by' => $approver->employee_id,
                'manager_approved_at' => now(),
                'status' => 'pending_hr',
            ]),
            'pending_hr' => $this->leaveRequestRepository->update($leaveRequest, [
                'hr_approved_by' => $approver->employee_id,
                'hr_approved_at' => now(),
                'status' => 'pending_director',
            ]),
            'pending_director' => $this->leaveRequestRepository->update($leaveRequest, [
                'director_approved_by' => $approver->employee_id,
                'director_approved_at' => now(),
                'status' => 'approved',
            ]),
            default => throw new \RuntimeException('Tahap approval tidak dikenali.'),
        };
    }

    public function reject(LeaveRequests $leaveRequest, string $reason, string $expectedStatus): LeaveRequests
    {
        if ($leaveRequest->status !== $expectedStatus) {
            throw new \RuntimeException('Pengajuan cuti tidak berada pada tahap yang sesuai untuk ditolak.');
        }

        return $this->leaveRequestRepository->update($leaveRequest, [
            'status' => 'rejected',
            'rejected_at_level' => $expectedStatus,
            'rejection_reason' => $reason,
        ]);
    }

    private function isDepartmentManager(Employees $employee): bool
    {
        return $employee->managedDepartments()->exists();
    }
}
