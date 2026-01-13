<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'days_requested',
        'reason',
        'leave_type',
        'status',
        'approval_date',
        'rejection_reason',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class)->whereNull('deleted_at');
    }
}

