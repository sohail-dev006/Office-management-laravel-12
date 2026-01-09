<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class SalaryController extends Controller
{
    // public function __construct()
    // {
    //     // Only admin can create, edit, delete salaries
    //     $this->middleware('role:admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    //     // All logged-in users can view index and generate their own salary
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
{
    $month = $request->month ?? now()->month;
    $year  = $request->year ?? now()->year;

    $user = auth()->user();

    $query = Salary::with('employee')
        ->where('month', $month)
        ->where('year', $year);

    // Filter only for non-admin & non-permission users
    if (!$user->hasRole('admin') && !$user->can('salary-list')) {
        $query->whereHas('employee', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    $salaries = $query->get();

    return view('salary.index', compact('salaries','month','year'));
}







    
    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('salary.create', compact('employees'));
    }

    // Store new salary
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'month' => 'required|numeric|min:1|max:12',
    //         'year' => 'required|numeric|min:2000',
    //         'gross_salary' => 'required|numeric|min:0',
    //     ]);

    //     $employee = Employee::findOrFail($data['employee_id']);
    //     $workingDays = $this->workingDays($data['month'], $data['year']);

    //     // For simplicity, deduction = 0 for now
    //     $deduction = 0;
    //     $netSalary = $data['gross_salary'] - $deduction;

    //     Salary::create([
    //         'employee_id' => $employee->id,
    //         'month' => $data['month'],
    //         'year' => $data['year'],
    //         'working_days' => $workingDays,
    //         'present_days' => 0,
    //         'absent_days' => 0,
    //         'gross_salary' => $data['gross_salary'],
    //         'deduction' => $deduction,
    //         'net_salary' => $netSalary,
    //     ]);

    //     return redirect()->route('salary.index')->with('success', 'Salary created successfully!');
    // }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2000',
        ]);

        $employee = Employee::findOrFail($data['employee_id']);

        $workingDays = $this->workingDays($data['month'], $data['year']);


        $presentDays = $employee->attendances()
            ->whereMonth('date', $data['month'])
            ->whereYear('date', $data['year'])
            ->count();

        
        $leaveDays = $employee->leaves()
            ->where('status', 'Approved')
            ->whereMonth('start_date', $data['month'])
            ->whereYear('start_date', $data['year'])
            ->sum('days_requested');

        $absentDays = max(0, $workingDays - ($presentDays + $leaveDays));

        $grossSalary = $employee->salary;
        $dailySalary = $grossSalary / $workingDays;
        $deduction = $absentDays * $dailySalary;
        $netSalary = $grossSalary - $deduction;

        Salary::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'month' => $data['month'],
                'year' => $data['year'],
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

        return redirect()->route('salary.index')
            ->with('success', 'Salary calculated & saved successfully!');
    }



    
    public function edit(Salary $salary)
    {
        $employees = Employee::all();
        return view('salary.edit', compact('salary', 'employees'));
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

    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salary.index')->with('success', 'Salary deleted!');
    }

    public function pdf(Salary $salary)
    {
        $pdf = PDF::loadView('salary.pdf', compact('salary'));
        return $pdf->download('salary-slip.pdf');
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
            ['employee_id' => $employee->id, 'month' => $month, 'year' => $year],
            [
                'working_days' => $workingDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'gross_salary' => $employee->salary,
                'deduction' => $deduction,
                'net_salary' => $netSalary,
                'leaves' => $leaveDays
            ]
        );

        return view('salary.show', compact('salary', 'employee'));
    }


    private function workingDays($month, $year)
    {
        $start = \Carbon\Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $days = 0;

        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!in_array($date->dayOfWeek, [\Carbon\Carbon::SATURDAY, \Carbon\Carbon::SUNDAY])) {
                $days++;
            }
        }

        return $days;
    }

}
