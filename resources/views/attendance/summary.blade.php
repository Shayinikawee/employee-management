@extends('layouts.app')
@section('title', 'Attendance Summary')
@section('page-title', 'Attendance Summary')

@section('content')
{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-xs text-slate-500 mb-1">Total Present</p>
        <p class="text-2xl font-bold text-emerald-600">{{ $overallPresent }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-xs text-slate-500 mb-1">Total Absent</p>
        <p class="text-2xl font-bold text-red-600">{{ $overallAbsent }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-xs text-slate-500 mb-1">Total Late</p>
        <p class="text-2xl font-bold text-amber-600">{{ $overallLate }}</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('attendance.summary') }}" class="flex flex-wrap items-center gap-2 mb-6">
    <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
    <span class="text-slate-400 text-sm">to</span>
    <input type="date" name="date_to" value="{{ $dateTo }}" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
    <select name="unit_id" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
        <option value="">All Units</option>
        @foreach($units as $unit)
            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-xl hover:bg-slate-700">Filter</button>
</form>

{{-- Summary Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Employee</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">PF Number</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Unit</th>
            <th class="text-center px-5 py-3 font-semibold text-emerald-600">Present</th>
            <th class="text-center px-5 py-3 font-semibold text-red-600">Absent</th>
            <th class="text-center px-5 py-3 font-semibold text-amber-600">Late</th>
            <th class="text-center px-5 py-3 font-semibold text-violet-600">On Leave</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($summary as $row)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3 font-medium text-slate-800">{{ $row->name }}</td>
                <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $row->pf_number }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $row->unit_name ?? '—' }}</td>
                <td class="px-5 py-3 text-center"><span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">{{ $row->present_days }}</span></td>
                <td class="px-5 py-3 text-center"><span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">{{ $row->absent_days }}</span></td>
                <td class="px-5 py-3 text-center"><span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">{{ $row->late_days }}</span></td>
                <td class="px-5 py-3 text-center"><span class="px-2 py-0.5 bg-violet-100 text-violet-700 rounded-full text-xs font-medium">{{ $row->leave_days }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">No attendance data for this period.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($summary->hasPages())<div class="px-5 py-3 border-t border-slate-100">{{ $summary->links() }}</div>@endif
</div>
@endsection
