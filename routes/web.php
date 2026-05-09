<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeLeaveController;

// ── Auth Routes ────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Public Registration Routes ─────────────────────
Route::get('/register', [RegistrationController::class, 'showForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

// ── Pending Approval Route (for authenticated but not approved users) ──
Route::get('/registration-pending', [RegistrationController::class, 'pending'])->name('registration.pending')->middleware('auth');

// ── Protected Routes ───────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Employees (view accessible to all auth users, mutations admin-only)
    Route::resource('employees', EmployeeController::class)->only(['index', 'show']);
    Route::resource('employees', EmployeeController::class)->except(['index', 'show'])->middleware('role:admin');
    Route::get('/employees-export', [EmployeeController::class, 'export'])->name('employees.export')->middleware('role:admin');

    // Units (admin-only)
    Route::resource('units', UnitController::class)->middleware('role:admin');

    // Admin: Employee Registrations
    Route::get('/admin/registrations', [RegistrationController::class, 'index'])->name('registrations.index')->middleware('role:admin');
    Route::post('/admin/registrations/{employee}/approve', [RegistrationController::class, 'approve'])->name('registrations.approve')->middleware('role:admin');
    Route::post('/admin/registrations/{employee}/reject', [RegistrationController::class, 'reject'])->name('registrations.reject')->middleware('role:admin');

    // Employee: Profile Management (approved employees only)
    Route::middleware('approved-employee')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Employee: Leave Management (approved employees only)
        Route::get('/my-leaves', [EmployeeLeaveController::class, 'index'])->name('employee-leaves.index');
        Route::get('/my-leaves/create', [EmployeeLeaveController::class, 'create'])->name('employee-leaves.create');
        Route::post('/my-leaves', [EmployeeLeaveController::class, 'store'])->name('employee-leaves.store');
        Route::get('/my-leaves/{leave}', [EmployeeLeaveController::class, 'show'])->name('employee-leaves.show');
        Route::get('/my-leave-balances', [EmployeeLeaveController::class, 'balances'])->name('employee-leaves.balances');
    });

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/import', [AttendanceController::class, 'showImport'])->name('attendance.import')->middleware('role:admin');
    Route::post('/attendance/import', [AttendanceController::class, 'import'])->name('attendance.import.store')->middleware('role:admin');
    Route::get('/attendance/summary', [AttendanceController::class, 'summary'])->name('attendance.summary')->middleware('role:admin');

    // Leaves
    Route::resource('leaves', LeaveController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve')->middleware('role:admin');
    Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject')->middleware('role:admin');
    Route::get('/leave-balance', [LeaveController::class, 'balance'])->name('leaves.balance');
    Route::get('/leave-balance/get', [LeaveController::class, 'getBalance'])->name('leaves.getBalance');

    // Holidays (admin-only management)
    Route::resource('holidays', HolidayController::class)->except(['show'])->middleware('role:admin');
});
