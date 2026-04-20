@extends('layouts.app')
@section('title', 'Holidays')
@section('page-title', 'Holiday Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('holidays.index') }}" class="flex items-center gap-2">
        <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search holidays..." class="pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 w-64">
        </div>
        <select name="type" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
            <option value="">All Types</option>
            @foreach(['Public Holiday','Mercantile Holiday','Special Holiday','Poya Day'] as $t)
                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-xl hover:bg-slate-700">Search</button>
    </form>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('holidays.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add Holiday
    </a>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3 font-semibold text-slate-600">#</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Holiday Name</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Date</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Day</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Type</th>
            @if(auth()->user()->isAdmin())
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Actions</th>
            @endif
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($holidays as $i => $holiday)
            <tr class="hover:bg-slate-50 {{ $holiday->holiday_date->isPast() ? 'opacity-60' : '' }}">
                <td class="px-5 py-3 text-slate-500">{{ $holidays->firstItem() + $i }}</td>
                <td class="px-5 py-3 font-medium text-slate-800">{{ $holiday->name }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $holiday->holiday_date->format('d M Y') }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $holiday->holiday_date->format('l') }}</td>
                <td class="px-5 py-3"><span class="px-2 py-0.5 bg-violet-50 text-violet-700 text-xs rounded-lg font-medium">{{ $holiday->holiday_type }}</span></td>
                @if(auth()->user()->isAdmin())
                <td class="px-5 py-3">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('holidays.edit', $holiday) }}" class="p-1.5 rounded-lg hover:bg-amber-50 text-slate-400 hover:text-amber-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                        <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                            <button class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No holidays found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($holidays->hasPages())<div class="px-5 py-3 border-t border-slate-100">{{ $holidays->links() }}</div>@endif
</div>
@endsection
