<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('employee')
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.index', compact('attendances'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('attendance.create', compact('employees'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date|in:' . Carbon::now('Asia/Karachi')->toDateString(),
        ]);

        $today = Carbon::now('Asia/Karachi')->toDateString();

        //  Check for weekend
        if (in_array(Carbon::parse($today)->dayOfWeek, [Carbon::SUNDAY])) {
            return back()->with('error', 'Cannot mark attendance on weekends!');
        }

        //  Check for holiday
        $holidays = [
            '2025-01-01',
            '2025-12-25',
            // Add more holidays here
        ];
        if (in_array($today, $holidays)) {
            return back()->with('error', 'Cannot mark attendance on holidays!');
        }

        //  Check if already marked
        $alreadyMarked = Attendance::where('employee_id', $request->employee_id)
            ->where('date', $today)
            ->exists();

        if ($alreadyMarked) {
            return back()->with('error', 'Attendance already marked for today!');
        }

        $checkInTime = Carbon::now('Asia/Karachi');
        $lateTime = Carbon::now('Asia/Karachi')->setTime(10, 30);
        $status = $checkInTime->gt($lateTime) ? 'late' : 'present';

        Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => $today,
            'check_in' => $checkInTime->format('H:i:s'),
            'status' => $status,
        ]);

        if ($status === 'late') {
            $lateDuration = $lateTime->diff($checkInTime);
            $lateString = $lateDuration->format('%h hours %i minutes');
            return back()->with('success', "Attendance saved! Employee is late by {$lateString}.");
        }

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Attendance marked successfully!');
    }




    public function checkIn(Employee $employee)
    {
        $today = Carbon::now('Asia/Karachi')->toDateString();

        // Check if already checked in
        if (Attendance::where('employee_id', $employee->id)->where('date', $today)->exists()) {
            return back()->with('error', 'Already checked in today!');
        }

        $now = Carbon::now('Asia/Karachi');
        $officeStart = Carbon::now('Asia/Karachi')->setTime(10, 30);

        // Determine status
        $status = $now->gt($officeStart) ? 'late' : 'present';

        // Save attendance
        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'check_in' => $now->format('H:i:s'),
            'status' => $status,
        ]);

        // If late, calculate duration
        if ($status === 'late') {
            $lateDuration = $officeStart->diff($now);
            $lateString = $lateDuration->format('%h hours %i minutes');
            return back()->with('success', "Check-in recorded! Employee is late by {$lateString}.");
        }

        return back()->with('success', 'Check-in recorded on time!');
    }



}
