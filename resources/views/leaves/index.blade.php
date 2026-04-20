@extends('layouts.app')
@section('title', 'Leave Management')
@section('page-title', 'Leave Management')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('leaves.index') }}" class="flex flex-wrap items-center gap-2">
        <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
            <option value="">All Status</option>
            @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="employee_id" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
            <option value="">All Employees</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('leaves.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Apply Leave
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3 font-semibold text-slate-600">#</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Employee</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Type</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">From</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">To</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Days</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Status</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Actions</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($leaves as $i => $leave)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3 text-slate-500">{{ $leaves->firstItem() + $i }}</td>
                <td class="px-5 py-3 font-medium text-slate-800">{{ $leave->employee->name ?? 'N/A' }}</td>
                <td class="px-5 py-3"><span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded-lg font-medium">{{ $leave->leaveType->name ?? '' }}</span></td>
                <td class="px-5 py-3 text-slate-600">{{ $leave->date_from?->format('d M Y') }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $leave->date_to?->format('d M Y') }}</td>
                <td class="px-5 py-3 text-slate-600 font-medium">{{ $leave->number_of_days }}</td>
                <td class="px-5 py-3">
                    @php $c = ['approved'=>'bg-emerald-100 text-emerald-700','rejected'=>'bg-red-100 text-red-700','pending'=>'bg-amber-100 text-amber-700']; @endphp
                    <span class="px-2 py-1 text-xs rounded-full font-medium {{ $c[$leave->status] ?? '' }}">{{ ucfirst($leave->status) }}</span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('leaves.show', $leave) }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">View</a>
                        @if(auth()->user()->isAdmin() && $leave->status === 'pending')
                        <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">@csrf
                            <button class="ml-2 text-xs text-emerald-600 hover:text-emerald-700 font-medium">Approve</button>
                        </form>
                        <button onclick="document.getElementById('reject-{{ $leave->id }}').classList.toggle('hidden')" class="ml-1 text-xs text-red-600 hover:text-red-700 font-medium">Reject</button>
                        @endif
                    </div>
                    @if(auth()->user()->isAdmin() && $leave->status === 'pending')
                    <form action="{{ route('leaves.reject', $leave) }}" method="POST" id="reject-{{ $leave->id }}" class="hidden mt-2">
                        @csrf
                        <input type="text" name="rejection_reason" placeholder="Reason..." required class="w-full px-2 py-1 border border-slate-200 rounded-lg text-xs mb-1">
                        <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded-lg">Confirm Reject</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-5 py-12 text-center text-slate-400">No leave applications found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($leaves->hasPages())<div class="px-5 py-3 border-t border-slate-100">{{ $leaves->links() }}</div>@endif
</div>
@endsection
