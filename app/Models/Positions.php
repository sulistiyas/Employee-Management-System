<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Positions extends Model
{
    protected $table = 'positions';

    protected $primaryKey = 'position_id';

    public $fillable = [
        'name',
        'level',
        'department_id',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employees::class, 'position_id', 'position_id');
    }
}
