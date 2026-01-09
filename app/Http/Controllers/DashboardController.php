<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Salary;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user = Auth::user();

        // Check if user is associated with an employee record
        $employee = Employee::where('user_id', $user->id)->first();

        // Get this month's salary for the employee
        $salary = $employee ? Salary::where('employee_id', $employee->id)
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first() : null;

        // Attendance summary
        $presentCount = $employee ? Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'Present')
            ->count() : 0;

        $leaveCount = $employee ? Leave::where('employee_id', $employee->id)
            ->where('status', 'Approved')
            ->whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->count() : 0;

        $absentCount = $employee ? Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'Absent')
            ->count() : 0;

        // Recent leaves
        $recentLeaves = $employee ? Leave::where('employee_id', $employee->id)
            ->orderBy('start_date', 'desc')
            ->limit(5)
            ->get() : collect();

        // Show **admin dashboard only for admins**
        if ($user->hasRole('admin')) {
            return view('dashboard', [
                'totalEmployees' => Employee::count(),
                'presentToday' => Attendance::whereDate('date', $today)->count(),
                'onLeaveToday' => Leave::where('status', 'Approved')
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->count(),
                'salaryThisMonth' => Salary::where('month', now()->month)
                    ->where('year', now()->year)
                    ->count(),
            ]);
        }

        // **All other users and employees see the user dashboard**
        return view('UserDashboard', compact(
            'user',
            'employee',
            'salary',
            'presentCount',
            'leaveCount',
            'absentCount',
            'recentLeaves'
        ));
    }

    // Redirect after login
    public function redirect()
    {
        return $this->index();
    }
}
