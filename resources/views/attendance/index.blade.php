@extends('layouts.app')
@section('title', 'Attendance')
@section('page-title', 'Attendance Tracking')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-wrap items-center gap-2">
        <input type="date" name="date" value="{{ $date }}" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30" onchange="this.form.submit()">
        <select name="unit_id" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
            <option value="">All Units</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
            @endforeach
        </select>
        <select name="employee_id" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
            <option value="">All Employees</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
            @endforeach
        </select>
    </form>
    <div class="flex items-center gap-2">
        <a href="{{ route('attendance.summary') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm rounded-xl hover:bg-slate-200">Summary</a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('attendance.import') }}" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all">Import Data</a>
        @endif
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3 font-semibold text-slate-600">#</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Employee</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">PF Number</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Date</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Check In</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Check Out</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Worked</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Status</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($attendances as $i => $att)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3 text-slate-500">{{ $attendances->firstItem() + $i }}</td>
                <td class="px-5 py-3 font-medium text-slate-800">{{ $att->employee->name ?? 'N/A' }}</td>
                <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $att->employee->pf_number ?? '' }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $att->date?->format('d M Y') }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $att->check_in_time ?? '—' }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $att->check_out_time ?? '—' }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $att->worked_hours ?? '—' }}</td>
                <td class="px-5 py-3">
                    @php $colors = ['present'=>'bg-emerald-100 text-emerald-700','late'=>'bg-amber-100 text-amber-700','absent'=>'bg-red-100 text-red-700','half_day'=>'bg-blue-100 text-blue-700','on_leave'=>'bg-violet-100 text-violet-700']; @endphp
                    <span class="px-2 py-1 text-xs rounded-full font-medium {{ $colors[$att->status] ?? 'bg-slate-100 text-slate-600' }}">{{ ucfirst(str_replace('_',' ',$att->status)) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-5 py-12 text-center text-slate-400">No attendance records for this date.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($attendances->hasPages())<div class="px-5 py-3 border-t border-slate-100">{{ $attendances->links() }}</div>@endif
</div>
@endsection
