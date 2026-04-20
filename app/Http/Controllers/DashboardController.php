<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Stats
        $totalEmployees = Employee::active()->count();
        $presentToday = Attendance::whereDate('date', $today)
            ->whereIn('status', ['present', 'late'])->count();
        $absentToday = $totalEmployees - $presentToday;
        $pendingLeaves = Leave::pending()->count();
        $onLeaveToday = Attendance::whereDate('date', $today)
            ->where('status', 'on_leave')->count();

        // Upcoming holidays
        $upcomingHolidays = Holiday::upcoming()->limit(5)->get();

        // Recent leave requests
        $recentLeaves = Leave::with(['employee', 'leaveType'])
            ->latest()
            ->limit(5)
            ->get();

        // Monthly attendance data for chart (last 6 months)
        $monthlyAttendance = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabel = $month->format('M Y');

            $present = Attendance::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->whereIn('status', ['present', 'late'])
                ->count();

            $absent = Attendance::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('status', 'absent')
                ->count();

            $late = Attendance::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('status', 'late')
                ->count();

            $monthlyAttendance[] = [
                'month' => $monthLabel,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
            ];
        }

        // Leave usage by type for chart
        $leaveUsage = Leave::where('status', 'approved')
            ->whereYear('date_from', $currentYear)
            ->selectRaw('leave_type_id, SUM(number_of_days) as total_days')
            ->groupBy('leave_type_id')
            ->with('leaveType')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->leaveType->name ?? 'Unknown',
                    'days' => $item->total_days,
                ];
            });

        // Unit-wise employee count
        $unitStats = Unit::withCount('activeEmployees')->get();

        return view('dashboard.index', compact(
            'totalEmployees',
            'presentToday',
            'absentToday',
            'pendingLeaves',
            'onLeaveToday',
            'upcomingHolidays',
            'recentLeaves',
            'monthlyAttendance',
            'leaveUsage',
            'unitStats'
        ));
    }
}
