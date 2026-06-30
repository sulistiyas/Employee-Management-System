<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequests extends Model
{
    protected $table = 'leave_requests';

    protected $primaryKey = 'leave_request_id';

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