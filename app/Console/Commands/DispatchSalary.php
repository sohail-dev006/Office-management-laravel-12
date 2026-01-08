<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Salary;
use App\Mail\SalarySlipMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Employee;
use Carbon\Carbon;

class DispatchSalary extends Command
{
    protected $signature = 'salary:dispatch {month?} {year?}';

    protected $description = 'Dispatch salary PDFs to employees via email';


    public function handle()
    {
        $month = $this->argument('month') ?? now()->month;
        $year  = $this->argument('year') ?? now()->year;

        $employees = Employee::with(['attendances', 'leaves', 'user'])->get();

        if ($employees->isEmpty()) {
            $this->warn('No employees found.');
            return;
        }

        foreach ($employees as $employee) {

            if (!$employee->user?->email) {
                $this->warn("Employee {$employee->id} has no email.");
                continue;
            }

            $workingDays = $this->workingDays($month, $year);

            $presentDays = $employee->attendances()
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->count();

            $leaveDays = $employee->leaves()
                ->where('status', 'Approved')
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->sum('days_requested');

            $absentDays = max(0, $workingDays - ($presentDays + $leaveDays));
            $dailySalary = $employee->salary / $workingDays;
            $deduction = $absentDays * $dailySalary;
            $netSalary = $employee->salary - $deduction;

            $salary = Salary::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'month' => $month,
                    'year' => $year,
                ],
                [
                    'working_days' => $workingDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'gross_salary' => $employee->salary,
                    'deduction' => $deduction,
                    'net_salary' => $netSalary,
                    'leaves' => $leaveDays,
                ]
            );

            Mail::to($employee->user->email)
                ->send(new SalarySlipMail($salary));

            $this->info("Sent salary to {$employee->user->email}");
        }

        $this->info("Salary generation + dispatch completed ");
    }

    private function workingDays($month, $year)
    {
        $start = \Carbon\Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $days = 0;

        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!in_array($date->dayOfWeek, [
                \Carbon\Carbon::SATURDAY,
                \Carbon\Carbon::SUNDAY
            ])) {
                $days++;
            }
        }

        return $days;
    }


}
