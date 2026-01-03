<?php


namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;

class LeaveSummaryController extends Controller
{
    public function show(Employee $employee)
    {
        $currentYear = Carbon::now()->year;

        $totalLeaves = $employee->leaves()->sum('days_requested');

        $yearLeaves = $employee->leaves()
            ->whereYear('start_date', $currentYear)
            ->sum('days_requested');

        $casualLeaves = $employee->leaves()
            ->where('leave_type', 'Casual')
            ->sum('days_requested');

        $holidayLeaves = $employee->leaves()
            ->where('leave_type', 'Holiday')
            ->sum('days_requested');

        $totalWorkingDays = $this->calculateWorkingDays($currentYear);

        $presentDays = $employee->attendances()
            ->whereYear('date', $currentYear)
            ->count();

        $absents = $totalWorkingDays - $presentDays - $yearLeaves;

        return view('leaves.summary', compact(
            'employee',
            'totalLeaves',
            'yearLeaves',
            'casualLeaves',
            'holidayLeaves',
            'absents'
        ));
    }

    private function calculateWorkingDays($year)
    {
        $start = Carbon::create($year, 1, 1);
        $end = Carbon::create($year, 12, 31);
        $workingDays = 0;

        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $workingDays++;
            }
        }

        return $workingDays;
    }
}

