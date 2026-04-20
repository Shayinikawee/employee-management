<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Http\Requests\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with(['employee', 'leaveType', 'approvedByUser'])
            ->filterByStatus($request->status)
            ->forEmployee($request->employee_id);

        if ($request->date_from) {
            $query->whereDate('date_from', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('date_to', '<=', $request->date_to);
        }

        $leaves = $query->latest()->paginate(15)->withQueryString();
        $employees = Employee::active()->orderBy('name')->get();

        return view('leaves.index', compact('leaves', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('name')->get();
        $leaveTypes = LeaveType::orderBy('name')->get();
        return view('leaves.create', compact('employees', 'leaveTypes'));
    }

    public function store(LeaveRequest $request)
    {
        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);
        $numberOfDays = $dateFrom->diffInDays($dateTo) + 1;

        $balance = LeaveBalance::where('employee_id', $request->employee_id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', $dateFrom->year)->first();

        if ($balance && $balance->remaining_days < $numberOfDays) {
            return back()->withInput()->withErrors([
                'leave_type_id' => "Insufficient leave balance. Available: {$balance->remaining_days} days."
            ]);
        }

        Leave::create([
            'employee_id' => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'number_of_days' => $numberOfDays,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave application submitted successfully.');
    }

    public function show(Leave $leave)
    {
        $leave->load(['employee', 'leaveType', 'approvedByUser']);
        return view('leaves.show', compact('leave'));
    }

    public function approve(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave has already been processed.');
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $balance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $leave->date_from->year)->first();

        if ($balance) {
            $balance->used_days += $leave->number_of_days;
            $balance->remaining_days = $balance->total_days - $balance->used_days;
            $balance->save();
        }

        return back()->with('success', 'Leave approved.');
    }

    public function reject(Request $request, Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave has already been processed.');
        }

        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Leave rejected.');
    }

    public function balance(Request $request)
    {
        $year = $request->year ?? now()->year;

        $balances = LeaveBalance::with(['employee', 'leaveType'])
            ->where('year', $year)
            ->when($request->employee_id, fn($q, $id) => $q->where('employee_id', $id))
            ->orderBy('employee_id')
            ->paginate(20)->withQueryString();

        $employees = Employee::active()->orderBy('name')->get();
        $leaveTypes = LeaveType::orderBy('name')->get();

        return view('leaves.balance', compact('balances', 'employees', 'leaveTypes', 'year'));
    }

    public function getBalance(Request $request)
    {
        $balance = LeaveBalance::where('employee_id', $request->employee_id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', $request->year ?? now()->year)->first();

        return response()->json([
            'remaining' => $balance ? $balance->remaining_days : 0,
            'total' => $balance ? $balance->total_days : 0,
            'used' => $balance ? $balance->used_days : 0,
        ]);
    }
}
