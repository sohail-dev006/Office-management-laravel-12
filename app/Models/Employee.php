<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class Employee extends Model
{
    use SoftDeletes;
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
        'user_id',
        'profile_image',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }


    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }


}
