@extends('layouts.app')
@section('title', 'Leave Details')
@section('page-title', 'Leave Application Details')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('leaves.index') }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-1 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back to Leaves
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-slate-800">Leave Application</h3>
            @php $c = ['approved'=>'bg-emerald-100 text-emerald-700','rejected'=>'bg-red-100 text-red-700','pending'=>'bg-amber-100 text-amber-700']; @endphp
            <span class="px-3 py-1 text-sm rounded-full font-medium {{ $c[$leave->status] ?? '' }}">{{ ucfirst($leave->status) }}</span>
        </div>

        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">Employee</dt><dd class="text-slate-800 font-medium">{{ $leave->employee->name ?? 'N/A' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Leave Type</dt><dd class="text-slate-800 font-medium">{{ $leave->leaveType->name ?? '' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">From</dt><dd class="text-slate-800">{{ $leave->date_from?->format('d M Y') }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">To</dt><dd class="text-slate-800">{{ $leave->date_to?->format('d M Y') }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Number of Days</dt><dd class="text-slate-800 font-bold">{{ $leave->number_of_days }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Reason</dt><dd class="text-slate-800 text-right max-w-xs">{{ $leave->reason }}</dd></div>
            @if($leave->approved_by)
            <div class="flex justify-between"><dt class="text-slate-500">Processed By</dt><dd class="text-slate-800">{{ $leave->approvedByUser->name ?? '' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Processed At</dt><dd class="text-slate-800">{{ $leave->approved_at?->format('d M Y H:i') }}</dd></div>
            @endif
            @if($leave->rejection_reason)
            <div class="mt-3 p-3 bg-red-50 rounded-xl"><p class="text-xs text-red-600 font-medium">Rejection Reason:</p><p class="text-sm text-red-700">{{ $leave->rejection_reason }}</p></div>
            @endif
        </dl>
    </div>

    @if(auth()->user()->isAdmin() && $leave->status === 'pending')
    <div class="flex items-center gap-3">
        <form action="{{ route('leaves.approve', $leave) }}" method="POST">@csrf
            <button class="px-6 py-2.5 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition-colors">Approve</button>
        </form>
        <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="flex-1 flex items-center gap-2">@csrf
            <input type="text" name="rejection_reason" placeholder="Rejection reason..." required class="flex-1 px-3 py-2.5 border border-slate-200 rounded-xl text-sm">
            <button class="px-6 py-2.5 bg-red-500 text-white font-medium rounded-xl hover:bg-red-600 transition-colors">Reject</button>
        </form>
    </div>
    @endif
</div>
@endsection
