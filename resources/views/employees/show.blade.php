@extends('layouts.app')
@section('title', $employee->name)
@section('page-title', 'Employee Details')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('employees.index') }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back to Employees
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-amber-500 text-white text-sm rounded-xl hover:bg-amber-600 transition-colors">Edit Employee</a>
        @endif
    </div>

    {{-- Profile Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-slate-800">{{ $employee->name }}</h2>
                <p class="text-sm text-slate-500">{{ $employee->current_designation }} · {{ $employee->grade }}</p>

            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Personal Details --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">Personal Information</h3>
            <dl class="space-y-3">
                @foreach([
                    ['PF Number', $employee->pf_number],
                    ['NIC', $employee->nic],
                    ['Email', $employee->email],
                    ['Contact', $employee->contact_number],
                    ['Date of Birth', $employee->date_of_birth?->format('d M Y')],
                    ['Address', $employee->address],
                ] as [$label, $value])
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">{{ $label }}</dt>
                    <dd class="text-slate-800 font-medium text-right">{{ $value }}</dd>
                </div>
                @endforeach
            </dl>
        </div>

        {{-- Employment Details --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">Employment Details</h3>
            <dl class="space-y-3">
                @foreach([
                    ['Grade', $employee->grade],
                    ['Designation', $employee->current_designation],
                    ['First Appointment', $employee->date_of_first_appointment?->format('d M Y')],
                    ['Confirmation', $employee->date_of_confirmation?->format('d M Y') ?? 'Pending'],
                ] as [$label, $value])
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">{{ $label }}</dt>
                    <dd class="text-slate-800 font-medium text-right">{{ $value }}</dd>
                </div>
                @endforeach
            </dl>
        </div>
    </div>

    {{-- Leave Balances --}}
    @if($employee->leaveBalances->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">Leave Balance ({{ date('Y') }})</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($employee->leaveBalances->where('year', date('Y')) as $bal)
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs text-slate-500 mb-1">{{ $bal->leaveType->name ?? '' }}</p>
                <p class="text-lg font-bold text-slate-800">{{ $bal->remaining_days }} <span class="text-xs font-normal text-slate-400">/ {{ $bal->total_days }} days</span></p>
                <div class="w-full h-1.5 bg-slate-200 rounded-full mt-2">
                    <div class="h-1.5 bg-amber-500 rounded-full" style="width: {{ $bal->total_days > 0 ? ($bal->used_days / $bal->total_days * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Work History --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">Previous Work History</h3>
        @forelse($employee->workHistories as $wh)
        <div class="flex gap-3 mb-3 p-3 bg-slate-50 rounded-xl">
            <div class="w-1 bg-amber-400 rounded-full shrink-0"></div>
            <div>
                <p class="text-sm font-medium text-slate-800">{{ $wh->position }}</p>
                <p class="text-xs text-slate-500">{{ $wh->organization }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $wh->from_date?->format('M Y') }} — {{ $wh->to_date?->format('M Y') ?? 'Present' }}</p>
            </div>
        </div>
        @empty
        <p class="text-sm text-slate-400 text-center py-4">No previous work history recorded.</p>
        @endforelse
    </div>
</div>
@endsection
