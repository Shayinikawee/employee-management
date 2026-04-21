<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidayController;

// ── Auth Routes ────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Protected Routes ───────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::get('/employees-export', [EmployeeController::class, 'export'])->name('employees.export');    Route::get('/employees-export', [EmployeeController::class, 'export'])->name('employees.export');
    Route::get('/employees-export', [EmployeeController::class, 'export'])->name('employees.export');

    // Units
    Route::resource('units', UnitController::class);

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/import', [AttendanceController::class, 'showImport'])->name('attendance.import');
    Route::post('/attendance/import', [AttendanceController::class, 'import'])->name('attendance.import.store');
    Route::get('/attendance/summary', [AttendanceController::class, 'summary'])->name('attendance.summary');

    // Leaves
    Route::resource('leaves', LeaveController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve')->middleware('role:admin');
    Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject')->middleware('role:admin');
    Route::get('/leave-balance', [LeaveController::class, 'balance'])->name('leaves.balance');
    Route::get('/leave-balance/get', [LeaveController::class, 'getBalance'])->name('leaves.getBalance');

    // Holidays
    Route::resource('holidays', HolidayController::class)->except(['show']);
});
