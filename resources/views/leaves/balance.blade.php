@extends('layouts.app')
@section('title', 'Leave Balances')
@section('page-title', 'Leave Balances')

@section('content')
<form method="GET" action="{{ route('leaves.balance') }}" class="flex flex-wrap items-center gap-2 mb-6">
    <select name="employee_id" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm" onchange="this.form.submit()">
        <option value="">All Employees</option>
        @foreach($employees as $emp)
            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
        @endforeach
    </select>
    <input type="number" name="year" value="{{ $year }}" min="2020" max="2030" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm w-24" onchange="this.form.submit()">
</form>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Employee</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Leave Type</th>
            <th class="text-center px-5 py-3 font-semibold text-slate-600">Total</th>
            <th class="text-center px-5 py-3 font-semibold text-slate-600">Used</th>
            <th class="text-center px-5 py-3 font-semibold text-slate-600">Remaining</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Usage</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($balances as $bal)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3 font-medium text-slate-800">{{ $bal->employee->name ?? 'N/A' }}</td>
                <td class="px-5 py-3"><span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded-lg font-medium">{{ $bal->leaveType->name ?? '' }}</span></td>
                <td class="px-5 py-3 text-center text-slate-600">{{ $bal->total_days }}</td>
                <td class="px-5 py-3 text-center text-slate-600">{{ $bal->used_days }}</td>
                <td class="px-5 py-3 text-center font-bold {{ $bal->remaining_days <= 2 ? 'text-red-600' : 'text-emerald-600' }}">{{ $bal->remaining_days }}</td>
                <td class="px-5 py-3">
                    <div class="w-full max-w-[120px] h-2 bg-slate-100 rounded-full">
                        <div class="h-2 bg-amber-500 rounded-full" style="width: {{ $bal->total_days > 0 ? ($bal->used_days / $bal->total_days * 100) : 0 }}%"></div>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No leave balance records.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($balances->hasPages())<div class="px-5 py-3 border-t border-slate-100">{{ $balances->links() }}</div>@endif
</div>
@endsection
