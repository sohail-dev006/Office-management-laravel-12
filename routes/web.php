<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveSummaryController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserPermissionController;



Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/employee', function () {
    return view('employee.index');
})->name('employee.index');

Route::middleware(['auth','permission:view-dashboard'])
    ->get('/dashboard',[DashboardController::class,'index'])
    ->name('dashboard');
Route::middleware(['auth','permission:add-salary'])
    ->get('/employees/{employee}/salary/{month}/{year}', 
        [SalaryController::class, 'generate'])
    ->name('salary.generate');

Route::middleware(['auth','permission:add-employee'])
    ->resource('employee', EmployeeController::class);

Route::middleware(['auth','permission:add-attendence'])
    ->resource('attendance', AttendanceController::class);

Route::middleware(['auth','permission:add-leave'])
    ->resource('leaves', LeaveController::class)
    ->parameters(['leaves' => 'leave']);

Route::middleware(['auth','permission:add-salary'])
    ->resource('salary', SalaryController::class);
// kjkdhgk

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/users', [UserPermissionController::class,'index'])->name('admin.users');
    Route::get('/admin/users/{user}/permissions', [UserPermissionController::class,'edit'])->name('admin.users.permissions');
    Route::post('/admin/users/{user}/permissions', [UserPermissionController::class,'update'])->name('admin.users.permissions.update');
    Route::get('/admin/users/create', [UserPermissionController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [UserPermissionController::class, 'store'])->name('admin.users.store');

});



require __DIR__.'/auth.php';
