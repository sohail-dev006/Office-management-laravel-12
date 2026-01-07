<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserPermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Salary routes
    Route::get('/salary', [SalaryController::class,'index'])->name('salary.index');
    Route::post('/salary/generate', [SalaryController::class,'generate'])->name('salary.generate');
    Route::get('/salary/{salary}/pdf', [SalaryController::class,'pdf'])->name('salary.pdf');
    Route::delete('/salary/{salary}', [SalaryController::class,'destroy'])->name('salary.destroy');
});

// Resource routes with permissions
Route::middleware(['auth','permission:add-employee'])->resource('employee', EmployeeController::class);
Route::middleware(['auth','permission:add-attendence'])->resource('attendance', AttendanceController::class);
Route::middleware(['auth','permission:add-leave'])->resource('leaves', LeaveController::class)
    ->parameters(['leaves' => 'leave']);
Route::middleware(['auth','permission:add-salary'])->resource('salary', SalaryController::class);

// Admin routes
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/users', [UserPermissionController::class,'index'])->name('admin.users');
    Route::get('/admin/users/create', [UserPermissionController::class,'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [UserPermissionController::class,'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/permissions', [UserPermissionController::class,'edit'])->name('admin.users.permissions');
    Route::post('/admin/users/{user}/permissions', [UserPermissionController::class,'update'])->name('admin.users.permissions.update');
    Route::delete('/admin/users/{user}', [UserPermissionController::class,'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';
