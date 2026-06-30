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
        'manager_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
        'director_approved_at' => 'datetime',
    ];

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'manager_approved_by',
        'manager_approved_at',
        'hr_approved_by',
        'hr_approved_at',
        'director_approved_by',
        'director_approved_at',
        'rejected_at_level',
        'rejection_reason',
    ];

    const STATUSES = [
        'pending_manager' => 'Menunggu Persetujuan Manager',
        'pending_hr' => 'Menunggu Persetujuan HR',
        'pending_director' => 'Menunggu Persetujuan Director',
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

    public function managerApprovedBy(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'manager_approved_by', 'employee_id');
    }

    public function hrApprovedBy(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'hr_approved_by', 'employee_id');
    }

    public function directorApprovedBy(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'director_approved_by', 'employee_id');
    }
}