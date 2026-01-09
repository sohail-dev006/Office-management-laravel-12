<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequest;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class LeaveController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Admin sees all leaves
            $leaves = Leave::with('employee')
                ->orderBy('start_date', 'desc') // use correct column
                ->get();
        } else {
            // Normal employee sees only their own leaves
            $leaves = Leave::with('employee')
                ->whereHas('employee', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('start_date', 'desc') // use correct column
                ->get();
        }

        return view('leaves.index', compact('leaves'));
    }



    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $employees = Employee::all();
        } else {
            $employees = Employee::where('user_id', $user->id)->get();
        }

        return view('leaves.create', compact('employees'));
    }

    public function updateStatus(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $leave->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Leave status updated successfully!');
    }




    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'start_date' => 'required|date|in:' . Carbon::now('Asia/Karachi')->toDateString(),
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //         'days_requested' => 'required|numeric',
    //         'leave_type' => 'required|string|in:Casual,Sick,Earned,Holiday',
    //         'reason' => 'required|string',
    //         'status' => 'required|in:Pending,Approved,Rejected',
    //     ]);

    //     $employee = Employee::findOrFail($validated['employee_id']);

    //      $attendanceExists = $employee->attendances()
    //         ->whereBetween('date', [
    //             $validated['start_date'],
    //             $validated['end_date']
    //         ])
    //         ->exists();

    //     if ($attendanceExists) {
    //         return back()
    //             ->withInput()
    //             ->withErrors([
    //                 'start_date' => 'Attendance already marked for one or more selected dates. Leave cannot be applied.'
    //             ]);
    //     }

    //     Leave::create($validated);

    //     return redirect()
    //         ->route('leaves.index')
    //         ->with('success', 'Leave applied successfully!');
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_requested' => 'required|numeric',
            'leave_type' => 'required|string|in:Casual,Sick,Earned,Holiday',
            'reason' => 'required|string',
        ]);

        $validated['status'] = 'Pending'; 

        $employee = Employee::findOrFail($validated['employee_id']);

        $attendanceExists = $employee->attendances()
            ->whereBetween('date', [
                $validated['start_date'],
                $validated['end_date']
            ])
            ->exists();

        if ($attendanceExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_date' => 'Attendance already marked for selected dates.'
                ]);
        }

        Leave::create($validated); 

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave applied successfully!');
    }



    public function edit(Leave $leave)
    {   
        $employees = Employee::orderBy('first_name')->get();
        return view('leaves.edit', compact('leave', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_requested' => 'required|numeric',
            'reason' => 'required|string',
            'leave_type' => 'required|string|in:Casual,Sick,Earned,Holiday',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $leave->update($validated);

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave updated successfully!');
    }



    public function summary(Employee $employee)
    {
        $currentYear = Carbon::now()->year;

        $totalLeaves = $employee->leaves()->sum('days_requested');
        $yearLeaves = $employee->leaves()->whereYear('start_date', $currentYear)->sum('days_requested');
        $casualLeaves = $employee->leaves()->where('leave_type', 'Casual')->sum('days_requested');
        $holidayLeaves = $employee->leaves()->where('leave_type', 'Holiday')->sum('days_requested');

        $totalWorkingDays = $this->calculateWorkingDays($currentYear);
        $presentDays = $employee->attendances()->whereYear('date', $currentYear)->count();
        $absents = $totalWorkingDays - $presentDays - $yearLeaves;

        return view('leaves.summary', compact(
            'employee', 'totalLeaves', 'yearLeaves', 
            'casualLeaves', 'holidayLeaves', 'absents'
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
    

    public function destroy(Leave $leave)
    {
        $leave->delete();

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave deleted successfully!');
    }

}
