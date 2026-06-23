<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Shifts;
use Illuminate\Pagination\LengthAwarePaginator;

class ShiftRepository
{
    public function getAll(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Shifts::withCount('employeeShifts')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('start_time')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $shiftId): ?Shifts
    {
        return Shifts::find($shiftId);
    }

    public function create(array $data): Shifts
    {
        return Shifts::create($data);
    }

    public function update(Shifts $shift, array $data): Shifts
    {
        $shift->update($data);

        return $shift;
    }

    public function delete(Shifts $shift): bool
    {
        return $shift->delete();
    }
}
