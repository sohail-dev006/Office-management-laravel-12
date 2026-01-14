<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Salary;
use App\Models\Employee;
use App\Mail\SalarySlipMail;
use Illuminate\Support\Facades\Mail;

class DispatchSalary extends Command
{
    protected $signature = 'salary:dispatch {month?} {year?}';
    protected $description = 'Send salary slips to all employees';

    public function handle()
    {
        $month = $this->argument('month') ?? now()->month;
        $year  = $this->argument('year') ?? now()->year;

        $employees = Employee::with('user')->get();

        foreach ($employees as $employee) {


            if (!$employee->user || !$employee->user->email) {
                $this->warn("Skipping employee ID {$employee->id}: No email found");
                continue;
            }

            $salary = Salary::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            if (!$salary) {
                $this->warn("Skipping employee ID {$employee->id}: No salary record for {$month}/{$year}");
                continue;
            }

            Mail::to($employee->user->email)
                ->queue(new SalarySlipMail($salary));

            $this->info("Salary slip sent to {$employee->user->email}");
        }

        $this->info('All salary emails have been dispatched.');
    }
}
