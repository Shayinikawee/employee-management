<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Http\Requests\LeaveRequest;
use Illuminate\Http\Request;
use App\Helpers\WorkingDaysCalculator;
use Carbon\Carbon;

class EmployeeLeaveController extends Controller
{
    /**
     * List the authenticated employee's leave requests.
     */
    public function index(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->isApproved()) {
            abort(403, 'Your registration is not yet approved.');
        }

        $query = Leave::where('employee_id', $employee->id)
            ->with('leaveType', 'approvedByUser');

        // Filter by status
        if ($request->status && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $leaves = $query->latest('date_from')->paginate(15)->withQueryString();

        return view('employees.leaves.index', compact('leaves'));
    }

    /**
     * Show the create leave form.
     */
    public function create()
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->isApproved()) {
            abort(403, 'Your registration is not yet approved.');
        }

        $leaveTypes = LeaveType::orderBy('name')->get();
        $leaveBalances = $employee->leaveBalances()
            ->where('year', now()->year)
            ->with('leaveType')
            ->get();

        return view('employees.leaves.create', compact('leaveTypes', 'leaveBalances'));
    }

    /**
     * Store a new leave request for the authenticated employee.
     */
    public function store(LeaveRequest $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->isApproved()) {
            abort(403, 'Your registration is not yet approved.');
        }

        // Ensure employee is only applying for themselves
        $request->merge(['employee_id' => $employee->id]);

        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);
        $numberOfDays = WorkingDaysCalculator::calculate($dateFrom, $dateTo);

        // Check leave balance
        $balance = LeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', $dateFrom->year)
            ->first();

        if (!$balance || $balance->remaining_days < $numberOfDays) {
            $available = $balance?->remaining_days ?? 0;
            return back()->withInput()->withErrors([
                'date_from' => "Insufficient leave balance. Available: {$available} days."
            ]);
        }

        Leave::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'number_of_days' => $numberOfDays,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('employee-leaves.index')
            ->with('success', 'Leave application submitted successfully.');
    }

    /**
     * Show a specific leave request.
     */
    public function show(Leave $leave)
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->isApproved()) {
            abort(403, 'Your registration is not yet approved.');
        }

        if ($leave->employee_id !== $employee->id) {
            abort(403, 'Unauthorized.');
        }

        $leave->load('leaveType', 'approvedByUser');

        return view('employees.leaves.show', compact('leave'));
    }

    /**
     * Show leave balances for the current year.
     */
    public function balances()
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->isApproved()) {
            abort(403, 'Your registration is not yet approved.');
        }

        $leaveBalances = $employee->leaveBalances()
            ->where('year', now()->year)
            ->with('leaveType')
            ->orderBy('id')
            ->get();

        return view('employees.leaves.balances', compact('leaveBalances'));
    }
}
