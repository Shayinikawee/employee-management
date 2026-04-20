@extends('layouts.app')
@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('employees.update', $employee) }}">
        @csrf @method('PUT')
        {{-- Personal Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-slate-800 mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach([
                    ['name', 'Full Name', 'text'],
                    ['pf_number', 'PF Number', 'text'],
                    ['nic', 'NIC', 'text'],
                    ['email', 'Email', 'email'],
                    ['contact_number', 'Contact Number', 'text'],
                    ['date_of_birth', 'Date of Birth', 'date'],
                ] as [$field, $label, $type])
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ $label }} <span class="text-red-500">*</span></label>
                    <input type="{{ $type }}" name="{{ $field }}" value="{{ old($field, $type === 'date' ? ($employee->$field?->format('Y-m-d') ?? '') : $employee->$field) }}" required
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">
                    @error($field)<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                @endforeach
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="2" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">{{ old('address', $employee->address) }}</textarea>
                    @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Employment Details --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-slate-800 mb-4">Employment Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Grade <span class="text-red-500">*</span></label>
                    <input type="text" name="grade" value="{{ old('grade', $employee->grade) }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Current Designation <span class="text-red-500">*</span></label>
                    <input type="text" name="current_designation" value="{{ old('current_designation', $employee->current_designation) }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
                    <select name="unit_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                        <option value="">— Select Unit —</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id', $employee->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date of First Appointment <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_first_appointment" value="{{ old('date_of_first_appointment', $employee->date_of_first_appointment?->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date of Confirmation</label>
                    <input type="date" name="date_of_confirmation" value="{{ old('date_of_confirmation', $employee->date_of_confirmation?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                </div>
                <div>
                    <label class="flex items-center gap-2 mt-6">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500/50">
                        <span class="text-sm font-medium text-slate-700">Active Employee</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Work History --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-slate-800">Previous Work History</h3>
                <button type="button" onclick="addWorkHistory()" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-200">+ Add Record</button>
            </div>
            <div id="work-history-container">
                @foreach($employee->workHistories as $i => $wh)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3 p-3 bg-slate-50 rounded-xl items-end work-history-row">
                    <div><label class="block text-xs font-medium text-slate-600 mb-1">From Date</label><input type="date" name="work_histories[{{ $i }}][from_date]" value="{{ $wh->from_date?->format('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-medium text-slate-600 mb-1">To Date</label><input type="date" name="work_histories[{{ $i }}][to_date]" value="{{ $wh->to_date?->format('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-medium text-slate-600 mb-1">Position</label><input type="text" name="work_histories[{{ $i }}][position]" value="{{ $wh->position }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-medium text-slate-600 mb-1">Organization</label><input type="text" name="work_histories[{{ $i }}][organization]" value="{{ $wh->organization }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                    <div><button type="button" onclick="this.closest('.work-history-row').remove()" class="w-full px-3 py-2 bg-red-50 text-red-600 text-sm rounded-lg hover:bg-red-100">Remove</button></div>
                </div>
                @endforeach
            </div>
            @if($employee->workHistories->isEmpty())
            <p id="no-history-msg" class="text-sm text-slate-400 text-center py-4">No previous work history.</p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all">Update Employee</button>
            <a href="{{ route('employees.show', $employee) }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let historyIndex = {{ $employee->workHistories->count() }};
function addWorkHistory() {
    const msg = document.getElementById('no-history-msg'); if(msg) msg.style.display='none';
    const i = historyIndex++;
    document.getElementById('work-history-container').insertAdjacentHTML('beforeend', `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3 p-3 bg-slate-50 rounded-xl items-end work-history-row">
            <div><label class="block text-xs font-medium text-slate-600 mb-1">From Date</label><input type="date" name="work_histories[${i}][from_date]" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
            <div><label class="block text-xs font-medium text-slate-600 mb-1">To Date</label><input type="date" name="work_histories[${i}][to_date]" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
            <div><label class="block text-xs font-medium text-slate-600 mb-1">Position</label><input type="text" name="work_histories[${i}][position]" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
            <div><label class="block text-xs font-medium text-slate-600 mb-1">Organization</label><input type="text" name="work_histories[${i}][organization]" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
            <div><button type="button" onclick="this.closest('.work-history-row').remove()" class="w-full px-3 py-2 bg-red-50 text-red-600 text-sm rounded-lg hover:bg-red-100">Remove</button></div>
        </div>`);
}
</script>
@endpush
@endsection
