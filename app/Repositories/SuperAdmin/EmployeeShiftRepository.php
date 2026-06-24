<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Employees;
use App\Models\EmployeeShifts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class EmployeeShiftRepository
{
    /**
     * Karyawan yang sedang aktif di shift tertentu (assignment terbaru per
     * karyawan yang sudah berlaku), untuk ditampilkan di halaman detail shift.
     */
    public function getActiveByShift(int $shiftId, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        // Ambil employee_shift_id terbaru per employee yang sudah berlaku,
        // lalu filter ke shift yang dimaksud.
        $latestIdsPerEmployee = EmployeeShifts::query()
            ->selectRaw('MAX(employee_shift_id) as id')
            ->where('effective_date', '<=', now()->toDateString())
            ->groupBy('employee_id');

        return EmployeeShifts::with('employee.department', 'employee.position', 'changedBy')
            ->whereIn('employee_shift_id', $latestIdsPerEmployee)
            ->where('shift_id', $shiftId)
            ->when($search, function ($query, $search) {
                $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('effective_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Karyawan yang BELUM memiliki shift aktif di shift manapun saat ini.
     * Digunakan sebagai daftar pilihan pada modal "Assign Karyawan".
     *
     * @return Collection<int, Employees>
     */
    public function getAvailableEmployees(?string $search = null): Collection
    {
        $employeesWithActiveShift = EmployeeShifts::query()
            ->select('employee_id')
            ->where('effective_date', '<=', now()->toDateString());
 
        return Employees::with('department', 'position')
            ->whereNotIn('employee_id', $employeesWithActiveShift)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('full_name')
            ->get();
    }

    /**
     * Assign banyak karyawan sekaligus ke satu shift dengan satu effective_date
     * yang sama. Mengembalikan jumlah assignment yang berhasil dibuat.
     */
    public function bulkAssign(array $employeeIds, int $shiftId, Carbon $effectiveDate, ?int $changedBy): int
    {
        $rows = array_map(fn (int $employeeId) => [
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'effective_date' => $effectiveDate->toDateString(),
            'changed_by' => $changedBy,
            'created_at' => now(),
            'updated_at' => now(),
        ], $employeeIds);

        EmployeeShifts::insert($rows);

        return count($rows);
    }

    /**
     * Copot (hapus) assignment aktif untuk sejumlah karyawan dari shift tertentu.
     * Hanya menghapus assignment yang sedang aktif (effective_date <= hari ini)
     * milik shift ini — histori assignment lama tetap tidak tersentuh.
     */
    public function removeActiveAssignments(array $employeeIds, int $shiftId): int
    {
        $latestIdsPerEmployee = EmployeeShifts::query()
            ->selectRaw('MAX(employee_shift_id) as id')
            ->where('effective_date', '<=', now()->toDateString())
            ->whereIn('employee_id', $employeeIds)
            ->groupBy('employee_id');

        return EmployeeShifts::whereIn('employee_shift_id', $latestIdsPerEmployee)
            ->where('shift_id', $shiftId)
            ->delete();
    }

    public function getHistoryByEmployee(int $employeeId): Collection
    {
        return EmployeeShifts::with('shift', 'changedBy')
            ->where('employee_id', $employeeId)
            ->orderByDesc('effective_date')
            ->get();
    }
}
