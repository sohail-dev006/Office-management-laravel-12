<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;



class SalaryController extends Controller
{
    public function index($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;


        $salaries = Salary::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        return view('salary.index', compact('salaries', 'month', 'year'));
    }


    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('salary.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2000',
            'gross_salary' => 'required|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($data['employee_id']);
        $workingDays = $this->workingDays($data['month'], $data['year']);
        $deduction = 0; // For simplicity, or calculate absences if needed
        $netSalary = $data['gross_salary'] - $deduction;

        Salary::create([
            'employee_id' => $employee->id,
            'month' => $data['month'],
            'year' => $data['year'],
            'working_days' => $workingDays,
            'present_days' => 0,
            'absent_days' => 0,
            'gross_salary' => $data['gross_salary'],
            'deduction' => $deduction,
            'net_salary' => $netSalary,
        ]);

        return redirect()->route('salary.index')->with('success', 'Salary created!');
    }

    public function update(Request $request, Salary $salary)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2000',
            'gross_salary' => 'required|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'net_salary' => 'nullable|numeric|min:0',
        ]);

        $salary->update([
            'employee_id' => $data['employee_id'],
            'month' => $data['month'],
            'year' => $data['year'],
            'gross_salary' => $data['gross_salary'],
            'deduction' => $data['deduction'] ?? 0,
            'net_salary' => $data['net_salary'] ?? ($data['gross_salary'] - ($data['deduction'] ?? 0)),
        ]);

        return redirect()->route('salary.index')->with('success', 'Salary updated!');
    }



    // public function index($month = null, $year = null)

    // {
    //     $month = $month ?? now()->month;
    //     $year = $year ?? now()->year;

    //     $employees = Employee::all();
    //     $salaries = [];

    //     foreach ($employees as $employee) {
    //         $workingDays = $this->workingDays($month, $year);

    //         $presentDays = $employee->attendances()
    //             ->whereMonth('date', $month)
    //             ->whereYear('date', $year)
    //             ->count();

    //         $leaveDays = $employee->leaves()
    //             ->where('status', 'Approved')
    //             ->whereMonth('start_date', $month)
    //             ->whereYear('start_date', $year)
    //             ->sum('days_requested');

    //         $absentDays = max(0, $workingDays - ($presentDays + $leaveDays));

    //         $dailySalary = $employee->salary / $workingDays;
    //         $deduction = $absentDays * $dailySalary;
    //         $netSalary = $employee->salary - $deduction;

    //         $salaries[] = [
    //             'employee' => $employee,
    //             'working_days' => $workingDays,
    //             'present_days' => $presentDays,
    //             'leave_days' => $leaveDays,
    //             'absent_days' => $absentDays,
    //             'gross_salary' => $employee->salary,
    //             'deduction' => $deduction,
    //             'net_salary' => $netSalary,
    //         ];
    //     }

    //     return view('salary.index', compact('salaries', 'month', 'year', 'employees'));
    // }
    // public function create()
    // {
    //     return view('employee.create');
    // }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'month' => 'required|numeric',
    //         'year' => 'required|numeric',
    //         'gross_salary' => 'required|numeric',
    //         'deduction' => 'required|numeric',
    //         'net_salary' => 'required|numeric',
    //     ]);

    //     Salary::create($data);
    //     return redirect()->route('salary.admin.index')->with('success', 'Salary created!');
    // }

    // public function edit(Salary $salary)
    // {
    //     $employees = Employee::all();
    //     return view('salary.edit', compact('salary', 'employees'));
    // }

    // public function update(Request $request, Salary $salary)
    // {
    //     $data = $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'month' => 'required|numeric',
    //         'year' => 'required|numeric',
    //         'gross_salary' => 'required|numeric',
    //     ]);

    //     $workingDays = $this->workingDays($data['month'], $data['year']);
    //     $dailySalary = $data['gross_salary'] / $workingDays;
    //     $deduction = 0;
    //     $netSalary = $data['gross_salary'] - $deduction;

    //     $salary->update([
    //         'employee_id' => $data['employee_id'],
    //         'month' => $data['month'],
    //         'year' => $data['year'],
    //         'gross_salary' => $data['gross_salary'],
    //         'deduction' => $deduction,
    //         'net_salary' => $netSalary,
    //     ]);

    //     return redirect()->route('salary.index')->with('success', 'Salary updated!');
    // }


    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salary.admin.index')->with('success', 'Salary deleted!');
    }

    public function generate(Employee $employee, $month, $year)
    {

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
            ]
        );

        return view('salary.show', compact('salary', 'employee'));
    }

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

