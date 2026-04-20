@extends('layouts.app')
@section('title', 'Employees')
@section('page-title', 'Employee Management')

@section('content')
{{-- Header Actions --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('employees.index') }}" class="flex items-center gap-2">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employees..."
                       class="pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 w-64">
            </div>
            <select name="unit_id" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                <option value="">All Units</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                @endforeach
            </select>
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-xl hover:bg-slate-700 transition-colors">Search</button>
        </form>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md hover:from-amber-600 hover:to-amber-700 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Employee
    </a>
    @endif
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">#</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Name</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">PF Number</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">NIC</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Designation</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Unit</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Status</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($employees as $index => $employee)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3 text-slate-500">{{ $employees->firstItem() + $index }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $employee->name }}</p>
                                <p class="text-xs text-slate-400">{{ $employee->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $employee->pf_number }}</td>
                    <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $employee->nic }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $employee->current_designation }}</td>
                    <td class="px-5 py-3">
                        @if($employee->unit)
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-medium">{{ $employee->unit->name }}</span>
                        @else
                            <span class="text-xs text-slate-400">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 text-xs rounded-full font-medium {{ $employee->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('employees.show', $employee) }}" class="p-1.5 rounded-lg hover:bg-blue-50 text-slate-400 hover:text-blue-600 transition-colors" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('employees.edit', $employee) }}" class="p-1.5 rounded-lg hover:bg-amber-50 text-slate-400 hover:text-amber-600 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p>No employees found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())
    <div class="px-5 py-3 border-t border-slate-100">
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection
