<?php

namespace App\Services\SuperAdmin;

use App\Models\Attendances;
use App\Models\Employees;
use App\Repositories\SuperAdmin\AttendanceRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class AttendanceService
{
    public function __construct(private AttendanceRepository $attendanceRepository) {}

    public function getAllAttendances(?string $search = null, ?string $status = null, ?string $date = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->attendanceRepository->getAll($search, $status, $date, $perPage);
    }

    public function findAttendance(int $attendanceId): ?Attendances
    {
        return $this->attendanceRepository->findById($attendanceId);
    }

    public function createAttendance(array $data): Attendances
    {
        $data = $this->applyCalculations($data);

        return $this->attendanceRepository->create($data);
    }

    public function updateAttendance(Attendances $attendance, array $data): Attendances
    {
        $data = $this->applyCalculations($data);

        return $this->attendanceRepository->update($attendance, $data);
    }

    public function deleteAttendance(Attendances $attendance): bool
    {
        return $this->attendanceRepository->delete($attendance);
    }

    public function deleteManyAttendances(array $attendanceIds): int
    {
        return $this->attendanceRepository->deleteMany($attendanceIds);
    }

    /**
     * Hitung late_minutes, work_minutes, dan attendance_status berdasarkan
     * shift aktif karyawan pada tanggal absensi. Jika status diisi manual
     * sebagai 'absent' atau 'permit', perhitungan jam dilewati.
     */
    private function applyCalculations(array $data): array
    {
        if (in_array($data['attendance_status'] ?? null, ['absent', 'permit'], true)) {
            $data['check_in'] = null;
            $data['check_out'] = null;
            $data['late_minutes'] = 0;
            $data['work_minutes'] = 0;

            return $data;
        }

        $employee = Employees::with('currentShift')->find($data['employee_id']);
        $shift = $employee?->currentShift;

        $lateMinutes = 0;
        $workMinutes = 0;

        if (! empty($data['check_in']) && $shift) {
            $checkIn = Carbon::parse($data['check_in']);
            $scheduledStart = Carbon::parse($shift->start_time)->setDate(
                $checkIn->year,
                $checkIn->month,
                $checkIn->day
            );

            if ($checkIn->greaterThan($scheduledStart)) {
                $minutesLate = $scheduledStart->diffInMinutes($checkIn);
                $lateMinutes = max(0, $minutesLate - $shift->late_tolerance_minutes);
            }
        }

        if (! empty($data['check_in']) && ! empty($data['check_out'])) {
            $checkIn = Carbon::parse($data['check_in']);
            $checkOut = Carbon::parse($data['check_out']);
            $workMinutes = $checkIn->diffInMinutes($checkOut);
        }

        $data['late_minutes'] = $lateMinutes;
        $data['work_minutes'] = $workMinutes;
        $data['attendance_status'] = $data['attendance_status'] ?? ($lateMinutes > 0 ? 'late' : 'present');

        return $data;
    }
}
