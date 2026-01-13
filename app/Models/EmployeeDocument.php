<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDocument extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'employee_id',
        'title',
        'type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

