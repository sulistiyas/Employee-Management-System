<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveTypes extends Model
{
    protected $table = 'leave_types';

    protected $primaryKey = 'leave_type_id';

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequests::class, 'leave_type_id', 'leave_type_id');
    }
}
