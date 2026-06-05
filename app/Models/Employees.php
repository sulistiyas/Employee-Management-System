<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employees extends Model
{
    protected $table = 'employees';

    protected $primaryKey = 'employee_id';

    public function department(): BelongsTo
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Positions::class, 'position_id', 'position_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'employee_id', 'employee_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendances::class, 'employee_id', 'employee_id');
    }

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShifts::class, 'employee_id', 'employee_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequests::class, 'employee_id', 'employee_id');
    }

    public function approvedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequests::class, 'approved_by', 'employee_id');
    }

    public function managedDepartments(): HasMany
    {
        return $this->hasMany(Departments::class, 'manager_employee_id', 'employee_id');
    }
}
