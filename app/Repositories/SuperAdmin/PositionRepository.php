<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Positions;
use Illuminate\Pagination\LengthAwarePaginator;

class PositionRepository
{
    public function getAll(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Positions::with('department')
            ->withCount('employees')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('level', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $positionId): ?Positions
    {
        return Positions::with('department')->find($positionId);
    }

    public function create(array $data): Positions
    {
        return Positions::create($data);
    }

    public function update(Positions $position, array $data): Positions
    {
        $position->update($data);

        return $position;
    }

    public function delete(Positions $position): bool
    {
        return $position->delete();
    }
}
