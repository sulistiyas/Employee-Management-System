<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shifts extends Model
{
    protected $table = 'shifts';
    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'code',
        'name',
        'start_time',
        'end_time',
        'late_tolerance_minutes',
    ];

    // Tambahkan konstanta ini
    const TYPES = [
        'pagi'  => ['label' => 'Pagi',  'prefix' => 'SH-PG'],
        'sore'  => ['label' => 'Sore',  'prefix' => 'SH-SR'],
        'malam' => ['label' => 'Malam', 'prefix' => 'SH-ML'],
    ];

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShifts::class, 'shift_id', 'shift_id');
    }
}
