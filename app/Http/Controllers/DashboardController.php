<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Salary;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

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
}
