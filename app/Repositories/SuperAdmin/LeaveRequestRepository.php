<?php

namespace App\Repositories;

use App\Models\LeaveRequests;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaveRequestRepository
{
    public function getAllByStatus(
        string $status,
        ?string $search = null,
        ?int $managerEmployeeId = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return LeaveRequests::with(['employee.department', 'leaveType'])
            ->where('status', $status)
            ->when($managerEmployeeId, function ($query) use ($managerEmployeeId) {
                $query->whereHas('employee.department', function ($q) use ($managerEmployeeId) {
                    $q->where('manager_employee_id', $managerEmployeeId);
                });
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('employee', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            })
            ->orderByDesc('created_at')
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
}