<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeShifts extends Model
{
    protected $table = 'employee_shifts';

    protected $primaryKey = 'employee_shift_id';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shifts::class, 'shift_id', 'shift_id');
    }
}
