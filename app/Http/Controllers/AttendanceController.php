<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeAttendanceMail;
use App\Models\User;

class AttendanceController extends Controller
{
    // List Attendance
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $attendances = Attendance::with('employee')->latest()->get();
        } else {
            $attendances = Attendance::whereHas('employee', fn($q) =>
                $q->where('user_id', $user->id)
            )->latest()->get();
        }

        return view('attendance.index', compact('attendances'));
    }


    public function create()
    {
        $user = auth()->user();
        $employees = $user->hasRole('admin') ? Employee::all() : Employee::where('user_id', $user->id)->get();
        return view('attendance.create', compact('employees'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date|in:' . Carbon::now('Asia/Karachi')->toDateString(),
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $today = Carbon::now('Asia/Karachi')->toDateString();

            $onLeave = $employee->leaves()
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        if ($onLeave) {
            return back()->with('error', 'Cannot mark attendance. Employee is on approved leave today!');
        }

        if (Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->exists()) {
            return back()->with('error', 'Attendance already marked today!');
        }

        $checkInTime = Carbon::now('Asia/Karachi');
        $lateTime = Carbon::now('Asia/Karachi')->setTime(10, 0); // Office start time
        $status = $checkInTime->gt($lateTime) ? 'late' : 'present';


        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'check_in' => $checkInTime,
            'status' => $status
        ]);


        if ($employee->user && $employee->user->email) {
            Mail::to($employee->user->email)->send(new EmployeeAttendanceMail($attendance));
        }


        if ($status === 'late') {
            $lateDuration = $lateTime->diff($checkInTime);
            $lateString = $lateDuration->format('%h hours %i minutes');
            return back()->with('error', "Attendance marked! Employee is late by {$lateString}.");
        }

        return back()->with('success', 'Attendance marked successfully on time!');
    }



    public function checkIn(Employee $employee)
    {
        $today = today('Asia/Karachi');


        $onLeave = $employee->leaves()
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        if ($onLeave) {
            return back()->with('error', 'Cannot check in. Employee is on approved leave today!');
        }

        if (Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->exists()) {
            return back()->with('error', 'Already checked in today');
        }

        $now = now('Asia/Karachi');
        $officeTime = Carbon::parse('10:20'); 

 
        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'check_in' => $now,
            'status' => $now->gt($officeTime) ? 'late' : 'present'
        ]);


        if ($employee->user && $employee->user->email) {
            Mail::to($employee->user->email)
                ->queue(new EmployeeAttendanceMail($attendance));
        }

 
        $adminEmails = User::role('admin')->pluck('email')->array();
        if ($adminEmails->isNotEmpty()) {
            Mail::to($adminEmails)
                ->queue(new EmployeeAttendanceMail($attendance));
        }

        // Late duration message
        if ($now->gt($officeTime)) {
            $lateDuration = $officeTime->diff($now);
            $lateString = $lateDuration->format('%h hours %i minutes');
            return back()->with('success', "Check-in successful! Employee is late by {$lateString}.");
        }

        return back()->with('success', 'Check-in successful on time!');
    }



    public function checkOut(Employee $employee)
    {
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today('Asia/Karachi'))
            ->firstOrFail();

        if ($attendance->check_out) {
            return back()->with('error','Already checked out');
        }

        $out = now('Asia/Karachi');
        $minutes = Carbon::parse($attendance->check_in)->diffInMinutes($out);

        $attendance->update([
            'check_out' => $out,
            'working_minutes' => $minutes
        ]);

        $this->lateFine($employee);

        if ($employee->user && $employee->user->email) {
            Mail::to($employee->user->email)
                ->queue(new EmployeeAttendanceMail($attendance));
        }

        $admins = \App\Models\User::role('admin')->pluck('email');
        foreach ($admins as $adminEmail) {
            Mail::to($adminEmail)
                ->queue(new EmployeeAttendanceMail($attendance));
        }

        return back()->with('success','Check-out successful!');
    }


    private function lateFine(Employee $employee)
    {
        $lates = Attendance::where('employee_id', $employee->id)
            ->where('status', 'late')
            ->latest()
            ->take(3)
            ->get();

        if ($lates->count() == 3) {
            foreach ($lates as $l) {
                $l->update(['is_fined' => true]);
            }
        }
    }
}
