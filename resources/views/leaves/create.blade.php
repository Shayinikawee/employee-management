@extends('layouts.app')
@section('title', 'Apply Leave')
@section('page-title', 'Apply for Leave')

@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('leaves.store') }}">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Employee <span class="text-red-500">*</span></label>
                    <select name="employee_id" id="employee_id" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30" onchange="checkBalance()">
                        <option value="">— Select Employee —</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->pf_number }})</option>
                        @endforeach
                    </select>
                    @error('employee_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Leave Type <span class="text-red-500">*</span></label>
                    <select name="leave_type_id" id="leave_type_id" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30" onchange="checkBalance()">
                        <option value="">— Select Type —</option>
                        @foreach($leaveTypes as $lt)
                            <option value="{{ $lt->id }}" {{ old('leave_type_id') == $lt->id ? 'selected' : '' }}>{{ $lt->name }} ({{ $lt->default_days }} days/year)</option>
                        @endforeach
                    </select>
                    @error('leave_type_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    <div id="balance-info" class="hidden mt-2 px-3 py-2 bg-blue-50 text-blue-700 text-xs rounded-lg"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">From Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date_from" id="date_from" value="{{ old('date_from') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30" onchange="calcDays()">
                        @error('date_from')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">To Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date_to" id="date_to" value="{{ old('date_to') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30" onchange="calcDays()">
                        @error('date_to')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div id="days-display" class="hidden px-3 py-2 bg-amber-50 text-amber-700 text-sm rounded-lg font-medium"></div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Reason <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="3" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">{{ old('reason') }}</textarea>
                    @error('reason')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all">Submit Application</button>
            <a href="{{ route('leaves.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function calcDays() {
    const from = document.getElementById('date_from').value;
    const to = document.getElementById('date_to').value;
    const display = document.getElementById('days-display');
    if (from && to) {
        const days = Math.ceil((new Date(to) - new Date(from)) / (1000*60*60*24)) + 1;
        if (days > 0) { display.textContent = `Number of days: ${days}`; display.classList.remove('hidden'); }
        else { display.classList.add('hidden'); }
    }
}

function checkBalance() {
    const empId = document.getElementById('employee_id').value;
    const typeId = document.getElementById('leave_type_id').value;
    const info = document.getElementById('balance-info');
    if (empId && typeId) {
        fetch(`{{ route('leaves.getBalance') }}?employee_id=${empId}&leave_type_id=${typeId}`)
            .then(r => r.json())
            .then(d => { info.innerHTML = `Remaining: <strong>${d.remaining}</strong> / ${d.total} days (Used: ${d.used})`; info.classList.remove('hidden'); });
    } else { info.classList.add('hidden'); }
}
</script>
@endpush
@endsection
