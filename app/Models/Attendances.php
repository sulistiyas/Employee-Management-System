<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendances extends Model
{
    protected $table = 'attendances';

    protected $primaryKey = 'attendance_id';

    protected $casts = [
        'attendance_date' => 'date',
    ];

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'late_minutes',
        'work_minutes',
        'attendance_status',
        'notes',
    ];

    const STATUSES = [
        'present' => 'Hadir',
        'late' => 'Terlambat',
        'absent' => 'Tidak Hadir',
        'permit' => 'Izin',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
    }
}
