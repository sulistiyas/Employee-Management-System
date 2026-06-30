<?php

namespace App\Repositories\SuperAdmin;

use App\Models\Positions;
use Illuminate\Pagination\LengthAwarePaginator;

class PositionRepository
{
    /**
     * Kolom yang boleh dipakai untuk sorting beserta mapping kolom aktualnya.
     */
    private const SORTABLE_COLUMNS = [
        'name' => 'name',
        'level' => 'level',
    ];

    public function getAll(
        ?string $search = null,
        ?string $sort = null,
        string $dir = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Positions::with('department')
            ->withCount('employees')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('level', 'like', "%{$search}%");
                });
            });

        if ($sort && array_key_exists($sort, self::SORTABLE_COLUMNS)) {
            $dir = $dir === 'desc' ? 'desc' : 'asc';
            $query->orderBy(self::SORTABLE_COLUMNS[$sort], $dir);
        } else {
            $query->orderBy('name');
        }

        return $query->paginate($perPage)->withQueryString();
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

    /**
     * Hapus banyak position sekaligus berdasarkan ID.
     * Position yang masih memiliki employees dilewati (tidak ikut dihapus).
     */
    public function deleteMany(array $positionIds): int
    {
        return Positions::whereIn('position_id', $positionIds)
            ->whereDoesntHave('employees')
            ->get()
            ->each(fn (Positions $position) => $position->delete())
            ->count();
    }
}