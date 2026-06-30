<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequests extends Model
{
    protected $table = 'leave_requests';

    protected $primaryKey = 'leave_request_id';

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    const STATUSES = [
        'pending' => 'Menunggu Persetujuan',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveTypes::class, 'leave_type_id', 'leave_type_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'approved_by', 'employee_id');
    }
<<<<<<< HEAD
<<<<<<< HEAD

    public function hrApprovedBy(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'hr_approved_by', 'employee_id');
    }

    public function directorApprovedBy(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'director_approved_by', 'employee_id');
    }
=======
>>>>>>> parent of 85933c2 (update)
=======
>>>>>>> parent of 85933c2 (update)
}
