<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{
    protected $fillable = [
        'first_name', 
        'last_name', 
        'designation', 
        'email',
        'phone',
        'password',
        'address',
        'gender',
        'dob',
        'join_date',
        'job_type',
        'city',
        'salary',
        'status',
        'age',
    ];

    protected $hidden = ['password'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }


}
