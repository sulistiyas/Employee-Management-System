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

    protected $fillable = [
        'employee_number',
        'full_name',
        'gender',
        'birth_date',
        'phone',
        'address',
        'join_date',
        'employment_status',
        'department_id',
        'position_id',
        'photo',
    ];

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

    /**
     * Shift yang sedang aktif untuk karyawan ini, yaitu assignment dengan
     * effective_date terbesar yang sudah berlaku (<= hari ini).
     */
    public function currentShift(): HasOne
    {
        return $this->hasOne(EmployeeShifts::class, 'employee_id', 'employee_id')
            ->active()
            ->orderByDesc('effective_date');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequests::class, 'employee_id', 'employee_id');
    }

    public function managerApprovedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequests::class, 'manager_approved_by', 'employee_id');
    }

    public function hrApprovedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequests::class, 'hr_approved_by', 'employee_id');
    }

    public function directorApprovedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequests::class, 'director_approved_by', 'employee_id');
    }

    public function managedDepartments(): HasMany
    {
        return $this->hasMany(Departments::class, 'manager_employee_id', 'employee_id');
    }
}