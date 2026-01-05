<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['role:HR, Developer, Salesperson, Manager']);

Route::middleware('auth')->group(function () {

    // ========== ROUTES TANPA MIDDLEWARE ROLE (UNTUK SEMUA USER) ==========
    // Hapus middleware role dari routes berikut:
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::resource('payrolls', PayrollController::class);
    Route::resource('presences', PresenceController::class);
    Route::resource('tasks', TaskController::class);
    
    Route::get('/tasks/done/{task}', [TaskController::class, 'done'])->name('tasks.done');
    Route::get('/tasks/pending/{task}', [TaskController::class, 'pending'])->name('tasks.pending');

    // ========== ROUTES DENGAN MIDDLEWARE ROLE (KHUSUS) ==========
    // Hanya untuk HR
    Route::middleware('role:HR')->group(function () {
        Route::get('leave-requests/confirm/{leaveRequest}', [LeaveRequestController::class, 'confirm'])
            ->name('leave-requests.confirm');
        Route::get('leave-requests/reject/{leaveRequest}', [LeaveRequestController::class, 'reject'])
            ->name('leave-requests.reject');
            
        Route::resource('roles', RoleController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('employees', EmployeeController::class);
    });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';