<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Attendances;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceRepository
{
    public function getAll(?string $search = null, ?string $status = null, ?string $date = null, int $perPage = 10): LengthAwarePaginator
    {
        return Attendances::with('employee')
            ->when($search, function ($query, $search) {
                $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('attendance_status', $status);
            })
            ->when($date, function ($query, $date) {
                $query->whereDate('attendance_date', $date);
            })
            ->orderByDesc('attendance_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $attendanceId): ?Attendances
    {
        return Attendances::find($attendanceId);
    }

    public function findByEmployeeAndDate(int $employeeId, string $date): ?Attendances
    {
        return Attendances::where('employee_id', $employeeId)
            ->whereDate('attendance_date', $date)
            ->first();
    }

    public function create(array $data): Attendances
    {
        return Attendances::create($data);
    }

    public function update(Attendances $attendance, array $data): Attendances
    {
        $attendance->update($data);

        return $attendance;
    }

    public function delete(Attendances $attendance): bool
    {
        return $attendance->delete();
    }

    public function deleteMany(array $attendanceIds): int
    {
        return Attendances::whereIn('attendance_id', $attendanceIds)
            ->get()
            ->each(fn (Attendances $attendance) => $attendance->delete())
            ->count();
    }
}
