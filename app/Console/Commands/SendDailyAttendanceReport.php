<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyAttendanceReport;

class SendDailyAttendanceReport extends Command
{
    protected $signature = 'attendance:daily-report {date?}';
    protected $description = 'Send daily attendance report to admins';

    public function handle()
    {
        // Get date argument or default to today
        $date = $this->argument('date') 
            ? Carbon::parse($this->argument('date'), 'Asia/Karachi')->toDateString() 
            : Carbon::today('Asia/Karachi')->toDateString();

        // Fetch all attendances for that date
        $attendances = Attendance::with(['employee', 'employee.leaves'])
                        ->whereDate('date', $date)
                        ->get();

        // Fetch employees with NO attendance for that date
        $employeesWithNoAttendance = Employee::whereDoesntHave('attendances', function($q) use ($date) {
            $q->whereDate('date', $date);
        })->get();

        foreach($employeesWithNoAttendance as $emp) {
            // Check if employee is on leave
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

        // Get all admin emails
        $admins = User::role('admin')->pluck('email')->toArray();

        if(!empty($admins)) {
            Mail::to($admins)->queue(new DailyAttendanceReport($attendances, $date));
            $this->info("Daily attendance report queued for admins for {$date}.");
        } else {
            $this->info('No admin emails found.');
        }

        return 0;
    }
}
