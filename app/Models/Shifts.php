<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shifts extends Model
{
    protected $table = 'shifts';

    protected $primaryKey = 'shift_id';

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShifts::class, 'shift_id', 'shift_id');
    }
}
