<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Employee;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    $employees = Employee::all();

    foreach ($employees as $emp) {
        for ($i = 1; $i <= 22; $i++) {
            Attendance::create([
                'employee_id' => $emp->id,
                'date' => now()->subDays($i),
                'status' => collect(['present','present','absent','leave'])->random(),
                'check_in' => '09:00:00',
                'check_out' => '17:00:00',
                'working_minutes' => 480
            ]);
        }
    }
}

}
