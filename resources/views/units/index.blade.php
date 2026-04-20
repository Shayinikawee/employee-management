@extends('layouts.app')
@section('title', 'Units')
@section('page-title', 'Unit Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('units.index') }}" class="flex items-center gap-2">
        <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search units..." class="pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 w-64">
        </div>
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-xl hover:bg-slate-700">Search</button>
    </form>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('units.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add Unit
    </a>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    @forelse($units as $unit)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
            </div>
            @if(auth()->user()->isAdmin())
            <div class="flex items-center gap-1">
                <a href="{{ route('units.edit', $unit) }}" class="p-1.5 rounded-lg hover:bg-amber-50 text-slate-400 hover:text-amber-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                <form action="{{ route('units.destroy', $unit) }}" method="POST" onsubmit="return confirm('Delete this unit?')">@csrf @method('DELETE')
                    <button class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </form>
            </div>
            @endif
        </div>
        <h3 class="text-base font-semibold text-slate-800">{{ $unit->name }}</h3>
        <p class="text-xs text-slate-400 mt-1 mb-3">{{ $unit->description ?? 'No description' }}</p>
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-500">{{ $unit->active_employees_count }} employee(s)</span>
            <a href="{{ route('units.show', $unit) }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">View Details →</a>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-slate-400">No units found.</div>
    @endforelse
</div>

@if($units->hasPages())
<div class="mt-6">{{ $units->links() }}</div>
@endif
@endsection
