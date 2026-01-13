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
        'leaves',

        // earnings
        'basic_salary',
        'house_allowance',
        'medical_allowance',
        'transport_allowance',
        'other_allowance',
        'bonus',

        // deductions
        'advance_salary',
        'tax',
        'other_deduction',
        'total_deductions',

        'gross_salary',
        'deduction',
        'net_salary',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


}

