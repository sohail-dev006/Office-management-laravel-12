<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'working_days',
        'present_days',
        'absent_days',
        'gross_salary',
        'deduction',
        'net_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}

