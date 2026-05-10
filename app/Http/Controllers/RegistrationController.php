<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Unit;
use App\Models\LeaveType;
use App\Http\Requests\StoreRegistrationRequest;
use App\Mail\RegistrationConfirmation;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    /**
     * Show the registration form (public).
     */
    public function showForm()
    {
        $units = Unit::orderBy('name')->get();
        return view('auth.register', compact('units'));
    }

    /**
     * Store a new registration (public).
     */
    public function store(StoreRegistrationRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'staff',
            ]);

            // Create employee record with pending status
            $employee = Employee::create([
                'user_id' => $user->id,
                'unit_id' => $request->unit_id,
                'name' => $request->name,
                'pf_number' => $request->pf_number,
                'email' => $request->email,
                'nic' => $request->nic,
                'date_of_birth' => $request->date_of_birth,
                'grade' => $request->grade,
                'current_designation' => $request->current_designation,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'date_of_first_appointment' => $request->date_of_first_appointment ?? now()->toDateString(),
                'is_active' => true,
                'approval_status' => 'pending',
            ]);

            // Queue confirmation email (async)
            Mail::to($user->email)->queue(new RegistrationConfirmation($employee));
        });

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please wait for admin approval before logging in.');
    }

    /**
     * Admin: List pending registrations.
     */
    public function index(Request $request)
    {


        $status = $request->query('status', 'pending');
        $query = Employee::query();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('approval_status', $status);
        }

        $employees = $query
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.registrations.index', compact('employees', 'status'));
    }

    /**
     * Admin: Approve a registration.
     */
    public function approve(Employee $employee)
    {
        if ($employee->approval_status !== 'pending') {
            return back()->with('error', 'Only pending registrations can be approved.');
        }

        $employee->approve();

        // Queue approval email (async)
        Mail::to($employee->user->email)->queue(new RegistrationApproved($employee));

        return back()->with('success', 'Employee registration approved successfully.');
    }

    /**
     * Admin: Reject a registration.
     */
    public function reject(Request $request, Employee $employee)
    {
        if ($employee->approval_status !== 'pending') {
            return back()->with('error', 'Only pending registrations can be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $employee->reject($validated['rejection_reason']);

        // Queue rejection email (async)
        Mail::to($employee->user->email)->queue(new RegistrationRejected($employee));

        return back()->with('success', 'Employee registration rejected.');
    }

    /**
     * Show pending approval message (for unapproved staff).
     */
    public function pending()
    {
        $employee = auth()->user()->employee;
        return view('auth.pending', compact('employee'));
    }
}
