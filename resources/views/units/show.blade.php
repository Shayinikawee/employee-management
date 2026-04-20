@extends('layouts.app')
@section('title', $unit->name)
@section('page-title', 'Unit Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('units.index') }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back to Units
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <h2 class="text-xl font-bold text-slate-800">{{ $unit->name }}</h2>
    <p class="text-sm text-slate-500 mt-1">{{ $unit->description ?? 'No description' }}</p>
    <p class="text-sm text-slate-600 mt-2"><strong>{{ $employees->total() }}</strong> employee(s) assigned</p>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('units.show', $unit) }}" class="mb-4">
    <div class="relative w-64">
        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employees..." class="pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 w-full">
    </div>
</form>

{{-- Employees Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Name</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">PF Number</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Designation</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Contact</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Actions</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($employees as $emp)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3 font-medium text-slate-800">{{ $emp->name }}</td>
                <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $emp->pf_number }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $emp->current_designation }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $emp->contact_number }}</td>
                <td class="px-5 py-3"><a href="{{ route('employees.show', $emp) }}" class="text-amber-600 hover:text-amber-700 text-xs font-medium">View →</a></td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">No employees in this unit.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($employees->hasPages())<div class="px-5 py-3 border-t border-slate-100">{{ $employees->links() }}</div>@endif
</div>
@endsection
