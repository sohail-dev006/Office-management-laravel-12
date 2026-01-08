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
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'redirect'])
            ->name('dashboard');
    });

    // Salary routes
    // Route::get('/salary', [SalaryController::class,'index'])->name('salary.index');
    // Route::post('/salary/generate', [SalaryController::class,'generate'])->name('salary.generate');
    // Route::get('/salary/{salary}/pdf', [SalaryController::class,'pdf'])->name('salary.pdf');
    // Route::delete('/salary/{salary}', [SalaryController::class,'destroy'])->name('salary.destroy');
});
Route::middleware(['auth','permission:salary-list'])->group(function () {
    Route::get('/salary', [SalaryController::class,'index'])->name('salary.index');
    Route::post('/salary/generate', [SalaryController::class,'generate'])->name('salary.generate');
    Route::get('/salary/{salary}/pdf', [SalaryController::class,'pdf'])->name('salary.pdf');
    Route::delete('/salary/{salary}', [SalaryController::class,'destroy'])->name('salary.destroy');
});


//  routes with permissions
Route::middleware('auth')->group(function () {
    // Employee list
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');


    // Add employee
    Route::get('/employees/create', [EmployeeController::class, 'create'])
        ->middleware('permission:add-employee')
        ->name('employee.create');

    Route::post('/employees', [EmployeeController::class, 'store'])
        ->middleware('permission:add-employee')
        ->name('employee.store');

    // Edit employee
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])
        ->middleware('permission:edit-employee')
        ->name('employee.edit');

    Route::patch('/employees/{employee}', [EmployeeController::class, 'update'])
        ->middleware('permission:edit-employee')
        ->name('employee.update');

    // Delete employee
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
        ->middleware('permission:delete-employee')
        ->name('employee.destroy');
});


Route::middleware(['auth','permission:attendance-list|add-attendance'])
    ->resource('attendance', AttendanceController::class);
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
