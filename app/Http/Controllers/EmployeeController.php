<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Unit;
use App\Models\WorkHistory;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with('unit')
            ->search($request->search)
            ->filterByUnit($request->unit_id);

        if ($request->status === 'active') {
            $query->active();
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $employees = $query->orderBy('name')->paginate(15)->withQueryString();
        $units = Unit::orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('employees._table', compact('employees'))->render(),
                'pagination' => $employees->links()->render(),
            ]);
        }

        return view('employees.index', compact('employees', 'units'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $units = Unit::orderBy('name')->get();
        return view('employees.create', compact('units'));
    }

    /**
     * Store a newly created employee.
     */
    public function store(EmployeeRequest $request)
    {
        DB::transaction(function () use ($request) {
            $employee = Employee::create($request->validated());

            // Create work histories
            if ($request->has('work_histories')) {
                foreach ($request->work_histories as $history) {
                    if (!empty($history['position']) && !empty($history['organization'])) {
                        $employee->workHistories()->create($history);
                    }
                }
            }

            // Initialize leave balances for current year
            $this->initializeLeaveBalances($employee);
        });

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $employee->load(['unit', 'workHistories', 'leaveBalances.leaveType']);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $employee->load('workHistories');
        $units = Unit::orderBy('name')->get();

        return view('employees.edit', compact('employee', 'units'));
    }

    /**
     * Update the specified employee.
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        DB::transaction(function () use ($request, $employee) {
            $employee->update($request->validated());

            // Sync work histories
            if ($request->has('work_histories')) {
                $employee->workHistories()->delete();
                foreach ($request->work_histories as $history) {
                    if (!empty($history['position']) && !empty($history['organization'])) {
                        $employee->workHistories()->create($history);
                    }
                }
            }
        });

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Initialize leave balances for a new employee.
     */
    private function initializeLeaveBalances(Employee $employee): void
    {
        $leaveTypes = LeaveType::all();
        $currentYear = now()->year;

        foreach ($leaveTypes as $type) {
            LeaveBalance::create([
                'employee_id' => $employee->id,
                'leave_type_id' => $type->id,
                'year' => $currentYear,
                'total_days' => $type->default_days,
                'used_days' => 0,
                'remaining_days' => $type->default_days,
            ]);
        }
    }
}
