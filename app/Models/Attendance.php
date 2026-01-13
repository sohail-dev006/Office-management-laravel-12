<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'working_minutes',
        'status',
        'is_fined'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getWorkingHoursAttribute()
    {
        if (!$this->working_minutes) return '-';
        return gmdate('H:i', $this->working_minutes * 60);
    }
}
