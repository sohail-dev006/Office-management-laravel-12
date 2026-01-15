<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalarySlipMail;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    // --- List Salaries ---
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;
        $search = $request->search;

        $salaries = Salary::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->when($search, function($q) use ($search) {
                $q->whereHas('employee', function($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name', 'like', "%$search%");
                });
            })
            ->get();

        return view('salary.index', compact('salaries', 'month', 'year'));
    }

    // --- Show Create Form ---
    public function create()
    {
        $employees = Employee::all();
        return view('salary.create', compact('employees'));
    }

    // --- Store / Generate Salary ---
    public function store(Request $request)
    {
        $employee = Employee::findOrFail($request->employee_id);
        $month = $request->month;
        $year  = $request->year;

        $monthStart = Carbon::create($year, $month, 1);
        $monthEnd   = $monthStart->copy()->endOfMonth();

        $workingDays = $this->calculateWorkingDays($monthStart, $monthEnd);

        $presentDays = $employee->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('status', ['present','late'])
            ->count();

        [$paidLeaves, $unpaidLeaves] = $this->calculateLeaves($employee, $monthStart, $monthEnd);

        $lateDays = $employee->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'late')
            ->count();

        $lateDeductionDays = intdiv($lateDays, 3);
        $absentDays = max(0, $workingDays - ($presentDays + $paidLeaves));
        $attendanceDeductionDays = $absentDays + $unpaidLeaves + $lateDeductionDays;

        // Base salary and daily calculation
        $basicSalary = $employee->salary; 
        $dailySalary = $basicSalary / $workingDays;

        // Allowances from request or 0
        $houseAllowance     = $request->house_allowance ?? 0;
        $medicalAllowance   = $request->medical_allowance ?? 0;
        $transportAllowance = $request->transport_allowance ?? 0;
        $otherAllowance     = $request->other_allowance ?? 0;
        $bonus              = $request->bonus ?? 0;

        // Deductions from request or 0
        $advanceSalary    = $request->advance_salary ?? 0;
        $tax              = $request->tax ?? 0;
        $otherDeduction   = $request->other_deduction ?? 0;

        // Gross salary
        $grossSalary = $basicSalary + $houseAllowance + $medicalAllowance + $transportAllowance + $otherAllowance + $bonus;

        // Attendance deductions
        $attendanceDeductionAmount = $attendanceDeductionDays * $dailySalary;

        // Total deductions
        $totalDeductions = $attendanceDeductionAmount + $advanceSalary + $tax + $otherDeduction;

        // Net salary
        $netSalary = $grossSalary - $totalDeductions;

        // Save or update salary
        Salary::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'month' => $month,
                'year'  => $year
            ],
            [
                'working_days'       => $workingDays,
                'present_days'       => $presentDays,
                'leaves'             => $paidLeaves,
                'absent_days'        => $absentDays,
                'late_days'          => $lateDays,
                'unpaid_leaves'      => $unpaidLeaves,
                'basic_salary'       => $basicSalary,
                'house_allowance'    => $houseAllowance,
                'medical_allowance'  => $medicalAllowance,
                'transport_allowance'=> $transportAllowance,
                'other_allowance'    => $otherAllowance,
                'bonus'              => $bonus,
                'gross_salary'       => $grossSalary,
                'advance_salary'     => $advanceSalary,
                'tax'                => $tax,
                'other_deduction'    => $otherDeduction,
                'deduction'          => $attendanceDeductionAmount,
                'total_deductions'   => $totalDeductions,
                'net_salary'         => $netSalary,
            ]
        );

        return back()->with('success', 'Salary generated successfully!');
    }

    // --- Show Edit Form ---
    public function edit(Salary $salary)
    {
        $employees = Employee::all();
        return view('salary.edit', compact('salary', 'employees'));
    }

    // --- Update Salary (manual / allowances & deductions) ---
    public function update(Request $request, Salary $salary)
    {
        $basicSalary = $request->basic_salary ?? $salary->basic_salary;
        $houseAllowance     = $request->house_allowance ?? 0;
        $medicalAllowance   = $request->medical_allowance ?? 0;
        $transportAllowance = $request->transport_allowance ?? 0;
        $otherAllowance     = $request->other_allowance ?? 0;
        $bonus              = $request->bonus ?? 0;

        $grossSalary = $basicSalary + $houseAllowance + $medicalAllowance + $transportAllowance + $otherAllowance + $bonus;

        $attendanceDeductionAmount = $salary->deduction ?? 0;
        $advanceSalary    = $request->advance_salary ?? 0;
        $tax              = $request->tax ?? 0;
        $otherDeduction   = $request->other_deduction ?? 0;

        $totalDeductions = $attendanceDeductionAmount + $advanceSalary + $tax + $otherDeduction;
        $netSalary = $grossSalary - $totalDeductions;

        $salary->update([
            'basic_salary'       => $basicSalary,
            'house_allowance'    => $houseAllowance,
            'medical_allowance'  => $medicalAllowance,
            'transport_allowance'=> $transportAllowance,
            'other_allowance'    => $otherAllowance,
            'bonus'              => $bonus,
            'gross_salary'       => $grossSalary,
            'advance_salary'     => $advanceSalary,
            'tax'                => $tax,
            'other_deduction'    => $otherDeduction,
            'total_deductions'   => $totalDeductions,
            'net_salary'         => $netSalary,
        ]);

        return redirect()->route('salary.index')->with('success', 'Salary updated successfully!');
    }

    // --- Show Salary Details ---
    public function show(Salary $salary)
    {
        return view('salary.show', compact('salary'));
    }

    // --- Generate PDF ---
    public function pdf(Salary $salary)
    {
        $pdf = Pdf::loadView('salary.pdf', ['salary' => $salary]);
        return $pdf->download('salary-slip-'.$salary->month.'-'.$salary->year.'.pdf');
    }

    // --- Dispatch Salary via Email ---
    public function dispatch(Salary $salary)
    {
        if($salary->employee->user && $salary->employee->user->email){
            Mail::to($salary->employee->user->email)->send(new SalarySlipMail($salary));
            return back()->with('success', 'Salary slip emailed successfully!');
        }
        return back()->with('error', 'Employee has no email!');
    }

    // --- Helpers ---
    private function calculateWorkingDays(Carbon $start, Carbon $end)
    {
        $days = 0;
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->dayOfWeek !== Carbon::SUNDAY) { // Exclude Sundays
                $days++;
            }
        }
        return $days;
    }

    private function calculateLeaves($employee, Carbon $monthStart, Carbon $monthEnd)
    {
        $paid = 0;
        $unpaid = 0;

        foreach ($employee->leaves()->where('status', 'Approved')->get() as $leave){
            $start = Carbon::parse($leave->start_date)->max($monthStart);
            $end   = Carbon::parse($leave->end_date)->min($monthEnd);

            if($start > $end) continue;

            $days = $start->diffInDays($end) + 1;

            if(in_array($leave->leave_type, ['Paid','Casual','Sick','Holiday'])) {
                $paid += $days;
            } else {
                $unpaid += $days;
            }
        }

        $paid = min($paid, 1); // Only 1 paid leave per month
        return [$paid, $unpaid];
    }
    // --- Delete Salary ---
    public function destroy(Salary $salary)
    {
        // Permission check
        if(!auth()->user()->can('delete-salary')){
            abort(403, 'Unauthorized action.');
        }

        $salary->delete();

        return redirect()->route('salary.index')->with('success', 'Salary deleted successfully!');
    }

}
