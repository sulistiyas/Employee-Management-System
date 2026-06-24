<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeShifts extends Model
{
    protected $table = 'employee_shifts';

    protected $primaryKey = 'employee_shift_id';

    protected $fillable = [
        'employee_id',
        'shift_id',
        'effective_date',
        'changed_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shifts::class, 'shift_id', 'shift_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }

    /**
     * Scope: hanya assignment yang sedang berlaku (effective_date <= hari ini).
     * Catatan: ini tidak otomatis "yang terbaru per karyawan" — gunakan bersama
     * pengelompokan/orderBy di repository untuk itu.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('effective_date', '<=', now()->toDateString());
    }
}