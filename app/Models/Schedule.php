<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'employee_id',
        'time_in',
        'time_out',
        
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
