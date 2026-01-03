<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveSummaryController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\DashboardController;




Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/employee', function () {
    return view('employee.index');
})->name('employee.index');

// Route::get('/attendance', function () {
//     return view('attendance.index');
// })->name('attendance.index');
// Route::middleware(['auth'])->group(function () {
//     Route::resource('employee', EmployeeController::class);
// });


Route::middleware('auth')->group(function () {

    Route::resource('employee', EmployeeController::class);

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // Check In / Check Out
    Route::post('/attendance/checkin/{employee}', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/checkout/{employee}', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');


    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit'); // Edit leave
    Route::put('/leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update'); // Update leave
    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');

    Route::get('/employees/{employee}/leave-summary', [LeaveSummaryController::class, 'show'])
    ->name('leaves.summary');


        // Salary CRUD routes
        Route::get('/salary', [SalaryController::class, 'index'])->name('salary.index');
        Route::get('/salary/create', [SalaryController::class, 'create'])->name('salary.create');
        Route::post('/salary', [SalaryController::class, 'store'])->name('salary.store');
        Route::get('/salary/{salary}/edit', [SalaryController::class, 'edit'])->name('salary.edit');
        Route::put('/salary/{salary}', [SalaryController::class, 'update'])->name('salary.update');
        Route::delete('/salary/{salary}', [SalaryController::class, 'destroy'])->name('salary.destroy');
        Route::get('/salary/{salary}', [SalaryController::class, 'show'])->name('salary.show');

        // Optional: generate salary for a specific employee/month/year
        Route::get('/employees/{employee}/salary/{month}/{year}', [SalaryController::class, 'generate'])->name('salary.generate');





});


require __DIR__.'/auth.php';
