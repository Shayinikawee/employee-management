<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WorkHistory;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::search($request->search);

        $employees = $query->orderBy('name')->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('employees._table', compact('employees'))->render(),
                'pagination' => $employees->links()->render(),
            ]);
        }

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('employees.create');
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
        $employee->load(['workHistories', 'leaveBalances.leaveType']);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $employee->load('workHistories');

        return view('employees.edit', compact('employee'));
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

    public function export(Request $request)
    {
        $query = Employee::search($request->search);

        $employees = $query->orderBy('name')->get();

        if ($request->format === 'csv') {
            return $this->exportCsv($employees);
        } elseif ($request->format === 'pdf') {
            return $this->exportPdf($employees);
        }
    }

    private function exportCsv($employees)
    {
        $filename = 'employees_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($employees) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'PF Number', 'Email', 'NIC', 'Contact', 'Designation', 'Grade']);
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->name,
                    $employee->pf_number,
                    $employee->email,
                    $employee->nic,
                    $employee->contact_number,
                    $employee->current_designation,
                    $employee->grade,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf($employees)
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $html = view('employees.export_pdf', compact('employees'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return $dompdf->stream('employees_' . now()->format('Y-m-d') . '.pdf');
    }
}
