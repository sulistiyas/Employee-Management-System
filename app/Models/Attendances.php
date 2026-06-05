<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendances extends Model
{
    protected $table = 'attendances';

    protected $primaryKey = 'attendance_id';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
    }
}
