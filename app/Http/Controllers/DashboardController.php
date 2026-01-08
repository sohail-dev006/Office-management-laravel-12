<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Salary;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

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

    public function UserDash()
    {
        $user = Auth::user(); 
        return view('UserDashboard', compact('user'));
    }

    public function redirect()
    {
        $user = Auth::user();

        if ($user->getAllPermissions()->isEmpty()) {
            return $this->UserDash(); 
        }


        return $this->index();
    }

}


