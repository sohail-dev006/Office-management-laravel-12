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

        $monthStart = Carbon::create($year, $month, 1)->startOfDay();
        $monthEnd = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        foreach ($employees as $employee) {

            if (!$employee->user?->email) {
                $this->warn("Employee {$employee->first_name} {$employee->last_name} ({$employee->id}) has no email.");
                continue;
            }

            $workingDays = $this->workingDays($month, $year);

            // --- Calculate present days ---
            $attendanceDates = $employee->attendances()
                ->where('date', '>=', $monthStart)
                ->where('date', '<=', $monthEnd)
                ->pluck('date')
                ->map(fn($d) => Carbon::parse($d)->toDateString())
                ->unique()
                ->values()
                ->toArray();

            $presentDays = count($attendanceDates);

            $this->info("Attendance Dates for {$employee->first_name} {$employee->last_name}: " . implode(', ', $attendanceDates));

            // --- Calculate approved leave days ---
            $leaveDays = $employee->leaves()
                ->where('status', 'Approved')
                ->where(function ($q) use ($monthStart, $monthEnd) {
                    $q->whereBetween('start_date', [$monthStart, $monthEnd])
                      ->orWhereBetween('end_date', [$monthStart, $monthEnd]);
                })
                ->get()
                ->sum(function($leave) use ($monthStart, $monthEnd) {
                    // Make sure leave days are capped within the month
                    $start = Carbon::parse($leave->start_date)->max($monthStart);
                    $end = Carbon::parse($leave->end_date)->min($monthEnd);
                    return $start->diffInDays($end) + 1; // +1 to include start day
                });

            // --- Calculate absent days ---
            $absentDays = max(0, $workingDays - ($presentDays + $leaveDays));

            // --- Salary calculation ---
            $grossSalary = $employee->salary;
            $dailySalary = $grossSalary / $workingDays;
            $deduction = $absentDays * $dailySalary;
            $netSalary = $grossSalary - $deduction;

            // --- Debug log ---
            $this->info("Employee: {$employee->first_name} {$employee->last_name} ({$employee->id})");
            $this->info("Working Days: {$workingDays}, Present: {$presentDays}, Leave: {$leaveDays}, Absent: {$absentDays}");
            $this->info("Gross: {$grossSalary}, Deduction: {$deduction}, Net: {$netSalary}");

            // --- Save salary ---
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
                    'gross_salary' => $grossSalary,
                    'deduction' => $deduction,
                    'net_salary' => $netSalary,
                    'leaves' => $leaveDays,
                ]
            );

            // --- Send email ---
            Mail::to($employee->user->email)
                ->send(new SalarySlipMail($salary));

            $this->info("Salary sent to: {$employee->user->email}");
            $this->info("---------------------------------------------------");
        }

        $this->info("Salary generation + dispatch completed.");
    }

    // --- Helper: calculate working days excluding weekends ---
    private function workingDays($month, $year)
    {
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $days = 0;

        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $days++;
            }
        }

        return $days;
    }
}
