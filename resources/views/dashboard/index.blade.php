@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- Total Employees --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">Total</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalEmployees }}</p>
        <p class="text-xs text-slate-500 mt-1">Active Employees</p>
    </div>

    {{-- Present Today --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Today</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $presentToday }}</p>
        <p class="text-xs text-slate-500 mt-1">Present Today</p>
    </div>

    {{-- Absent Today --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-lg">Today</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $absentToday }}</p>
        <p class="text-xs text-slate-500 mt-1">Absent / Not Checked In</p>
    </div>

    {{-- Pending Leaves --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Pending</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $pendingLeaves }}</p>
        <p class="text-xs text-slate-500 mt-1">Leave Requests</p>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Monthly Attendance Chart --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">Monthly Attendance Trend</h3>
        <canvas id="attendanceChart" height="200"></canvas>
    </div>

    {{-- Leave Usage Chart --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">Leave Usage by Type ({{ date('Y') }})</h3>
        <canvas id="leaveChart" height="200"></canvas>
    </div>
</div>

{{-- Bottom Section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Upcoming Holidays --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Upcoming Holidays</h3>
            <a href="{{ route('holidays.index') }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">View All →</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($upcomingHolidays as $holiday)
                <div class="px-5 py-3 flex items-center gap-3 hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-violet-100 flex flex-col items-center justify-center text-violet-700">
                        <span class="text-[10px] font-semibold uppercase leading-none">{{ $holiday->holiday_date->format('M') }}</span>
                        <span class="text-sm font-bold leading-none">{{ $holiday->holiday_date->format('d') }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700">{{ $holiday->name }}</p>
                        <p class="text-xs text-slate-400">{{ $holiday->holiday_type }} · {{ $holiday->holiday_date->format('l') }}</p>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">No upcoming holidays</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Leave Requests --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Recent Leave Requests</h3>
            <a href="{{ route('leaves.index') }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">View All →</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentLeaves as $leave)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-slate-700">{{ $leave->employee->name ?? 'N/A' }}</p>
                        <p class="text-xs text-slate-400">{{ $leave->leaveType->name ?? '' }} · {{ $leave->number_of_days }} day(s)</p>
                    </div>
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full
                        {{ $leave->status === 'approved' ? 'bg-emerald-100 text-emerald-700' :
                           ($leave->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($leave->status) }}
                    </span>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">No leave requests yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyData = @json($monthlyAttendance);
    const leaveData = @json($leaveUsage);

    // Attendance Chart
    if (document.getElementById('attendanceChart')) {
        const ctx1 = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => d.month),
                datasets: [
                    { label: 'Present', data: monthlyData.map(d => d.present), backgroundColor: 'rgba(16, 185, 129, 0.8)', borderRadius: 6 },
                    { label: 'Absent', data: monthlyData.map(d => d.absent), backgroundColor: 'rgba(239, 68, 68, 0.8)', borderRadius: 6 },
                    { label: 'Late', data: monthlyData.map(d => d.late), backgroundColor: 'rgba(245, 158, 11, 0.8)', borderRadius: 6 },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } },
                scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
            }
        });
    }

    // Leave Usage Chart
    if (document.getElementById('leaveChart')) {
        const ctx2 = document.getElementById('leaveChart').getContext('2d');
        const colors = ['rgba(99, 102, 241, 0.8)', 'rgba(236, 72, 153, 0.8)', 'rgba(14, 165, 233, 0.8)', 'rgba(168, 85, 247, 0.8)'];
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: leaveData.map(d => d.type),
                datasets: [{ data: leaveData.map(d => d.days), backgroundColor: colors, borderWidth: 0, hoverOffset: 10 }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } }
            }
        });
    }
});
</script>
@endpush
