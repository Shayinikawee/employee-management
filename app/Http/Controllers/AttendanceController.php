<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance listing.
     */
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::today()->toDateString();

        $query = Attendance::with('employee.unit')
            ->forDate($date)
            ->forEmployee($request->employee_id)
            ->forUnit($request->unit_id);

        $attendances = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $employees = Employee::active()->orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('attendance.index', compact('attendances', 'employees', 'units', 'date'));
    }

    /**
     * Show the import form.
     */
    public function showImport()
    {
        return view('attendance.import');
    }

    /**
     * Import attendance from CSV/Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $importBatch = 'BATCH-' . now()->format('YmdHis');
        $imported = 0;
        $errors = [];

        try {
            $extension = $file->getClientOriginalExtension();

            if (in_array($extension, ['csv', 'txt'])) {
                $handle = fopen($file->getRealPath(), 'r');
                $header = fgetcsv($handle);

                // Normalize headers
                $header = array_map(function ($h) {
                    return strtolower(trim(str_replace(' ', '_', $h)));
                }, $header);

                $row = 1;
                while (($data = fgetcsv($handle)) !== false) {
                    $row++;
                    try {
                        $record = array_combine($header, $data);

                        $employee = Employee::where('pf_number', trim($record['pf_number'] ?? ''))
                            ->first();

                        if (!$employee) {
                            $errors[] = "Row {$row}: Employee with PF Number '{$record['pf_number']}' not found.";
                            continue;
                        }

                        $date = Carbon::parse($record['date'] ?? '')->toDateString();
                        $checkIn = !empty($record['check_in_time']) ? $record['check_in_time'] : null;
                        $checkOut = !empty($record['check_out_time']) ? $record['check_out_time'] : null;

                        // Determine status
                        $status = 'present';
                        if (!$checkIn && !$checkOut) {
                            $status = 'absent';
                        } elseif ($checkIn && Carbon::parse($checkIn)->gt(Carbon::parse('08:30:00'))) {
                            $status = 'late';
                        }

                        Attendance::updateOrCreate(
                            [
                                'employee_id' => $employee->id,
                                'date' => $date,
                            ],
                            [
                                'check_in_time' => $checkIn,
                                'check_out_time' => $checkOut,
                                'status' => $status,
                                'import_batch' => $importBatch,
                            ]
                        );

                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Row {$row}: " . $e->getMessage();
                    }
                }

                fclose($handle);
            }
        } catch (\Exception $e) {
            return redirect()->route('attendance.import')
                ->with('error', 'Failed to import file: ' . $e->getMessage());
        }

        $message = "{$imported} attendance records imported successfully.";
        if (!empty($errors)) {
            $message .= ' ' . count($errors) . ' error(s) encountered.';
        }

        return redirect()->route('attendance.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    /**
     * Display attendance summary.
     */
    public function summary(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? Carbon::today()->toDateString();

        $summary = DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->leftJoin('units', 'employees.unit_id', '=', 'units.id')
            ->whereBetween('attendances.date', [$dateFrom, $dateTo])
            ->when($request->unit_id, function ($q, $unitId) {
                $q->where('employees.unit_id', $unitId);
            })
            ->select(
                'employees.id',
                'employees.name',
                'employees.pf_number',
                'units.name as unit_name',
                DB::raw("SUM(CASE WHEN attendances.status IN ('present', 'late') THEN 1 ELSE 0 END) as present_days"),
                DB::raw("SUM(CASE WHEN attendances.status = 'absent' THEN 1 ELSE 0 END) as absent_days"),
                DB::raw("SUM(CASE WHEN attendances.status = 'late' THEN 1 ELSE 0 END) as late_days"),
                DB::raw("SUM(CASE WHEN attendances.status = 'on_leave' THEN 1 ELSE 0 END) as leave_days")
            )
            ->groupBy('employees.id', 'employees.name', 'employees.pf_number', 'units.name')
            ->orderBy('employees.name')
            ->paginate(20)
            ->withQueryString();

        $units = Unit::orderBy('name')->get();

        // Overall summary counts
        $overallPresent = Attendance::dateRange($dateFrom, $dateTo)
            ->whereIn('status', ['present', 'late'])->count();
        $overallAbsent = Attendance::dateRange($dateFrom, $dateTo)
            ->where('status', 'absent')->count();
        $overallLate = Attendance::dateRange($dateFrom, $dateTo)
            ->where('status', 'late')->count();

        return view('attendance.summary', compact(
            'summary', 'units', 'dateFrom', 'dateTo',
            'overallPresent', 'overallAbsent', 'overallLate'
        ));
    }
}
