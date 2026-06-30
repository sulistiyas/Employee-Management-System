<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departments extends Model
{
    protected $table = 'departments';

    protected $primaryKey = 'department_id';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function departmentManager(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'manager_employee_id', 'employee_id');
    }

    public function departmentHr(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'hr_employee_id', 'employee_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Positions::class, 'department_id', 'department_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employees::class, 'department_id', 'department_id');
    }
}