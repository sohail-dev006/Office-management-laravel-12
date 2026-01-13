<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyAttendanceReport;

class SendDailyAttendanceReport extends Command
{
    protected $signature = 'attendance:daily-report {date?}';
    protected $description = 'Send daily attendance report to admins';

    public function handle()
    {

        $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'), 'Asia/Karachi')->toDateString()
            : Carbon::today('Asia/Karachi')->toDateString();


        $attendances = Attendance::with(['employee', 'employee.leaves'])
            ->whereDate('date', $date)
            ->get();


        $employeesWithNoAttendance = Employee::whereDoesntHave('attendances', function($q) use ($date) {
            $q->whereDate('date', $date);
        })->get();

        foreach ($employeesWithNoAttendance as $emp) {
            $leave = $emp->leaves()
                        ->where('status', 'Approved')
                        ->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date)
                        ->first();

            $attendances->push((object)[
                'employee' => $emp,
                'check_in' => null,
                'check_out' => null,
                'working_hours' => null,
                'status' => $leave ? 'leave' : 'absent',
                'leave' => $leave,
                'absent_reason' => $emp->absent_reason ?? null,
            ]);
        }

        //  Convert to plain array for queue compatibility
        $attendanceArray = $attendances->map(function($att) {
            return [
                'employee_name' => $att->employee->first_name . ' ' . ($att->employee->last_name ?? ''),
                'check_in' => $att->check_in,
                'check_out' => $att->check_out,
                'working_hours' => $att->working_hours,
                'status' => $att->status,
                'leave_reason' => $att->leave?->reason ?? null,
                'absent_reason' => $att->absent_reason ?? null,
            ];
        })->toArray();

        $admins = User::role('admin')->pluck('email')->toArray();

        if (!empty($admins)) {
            Mail::to($admins)->send(new DailyAttendanceReport($attendanceArray, $date));
            $this->info("Daily attendance report sent to admins for {$date}.");
        } else {
            $this->info('No admin emails found.');
        }

        return 0;
    }
}
