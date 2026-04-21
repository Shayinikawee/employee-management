@extends('layouts.app')
@section('title', 'Add Employee')
@section('page-title', 'Add New Employee')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('employees.store') }}">
        @csrf
        {{-- Personal Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-slate-800 mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach([
                    ['name', 'Full Name', 'text', true],
                    ['pf_number', 'PF Number', 'text', true],
                    ['nic', 'NIC', 'text', true],
                    ['email', 'Email', 'email', true],
                    ['contact_number', 'Contact Number', 'text', true],
                    ['date_of_birth', 'Date of Birth', 'date', true],
                ] as [$field, $label, $type, $req])
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ $label }} @if($req)<span class="text-red-500">*</span>@endif</label>
                    <input type="{{ $type }}" name="{{ $field }}" value="{{ old($field) }}" {{ $req ? 'required' : '' }}
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">
                    @error($field)<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                @endforeach
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="2" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">{{ old('address') }}</textarea>
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
                    <input type="text" name="grade" value="{{ old('grade') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">
                    @error('grade')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Current Designation <span class="text-red-500">*</span></label>
                    <input type="text" name="current_designation" value="{{ old('current_designation') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">
                    @error('current_designation')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date of First Appointment <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_first_appointment" value="{{ old('date_of_first_appointment') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date of Confirmation</label>
                    <input type="date" name="date_of_confirmation" value="{{ old('date_of_confirmation') }}" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">
                </div>
            </div>
        </div>

        {{-- Work History --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-slate-800">Previous Work History</h3>
                <button type="button" onclick="addWorkHistory()" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-200">+ Add Record</button>
            </div>
            <div id="work-history-container"></div>
            <p id="no-history-msg" class="text-sm text-slate-400 text-center py-4">No previous work history added.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all">Save Employee</button>
            <a href="{{ route('employees.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let historyIndex = 0;
function addWorkHistory() {
    document.getElementById('no-history-msg').style.display = 'none';
    const c = document.getElementById('work-history-container');
    const i = historyIndex++;
    c.insertAdjacentHTML('beforeend', `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3 p-3 bg-slate-50 rounded-xl items-end work-history-row">
            <div><label class="block text-xs font-medium text-slate-600 mb-1">From Date</label><input type="date" name="work_histories[${i}][from_date]" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
            <div><label class="block text-xs font-medium text-slate-600 mb-1">To Date</label><input type="date" name="work_histories[${i}][to_date]" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
            <div><label class="block text-xs font-medium text-slate-600 mb-1">Position</label><input type="text" name="work_histories[${i}][position]" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
            <div><label class="block text-xs font-medium text-slate-600 mb-1">Organization</label><input type="text" name="work_histories[${i}][organization]" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
            <div><button type="button" onclick="this.closest('.work-history-row').remove();if(!document.querySelector('.work-history-row'))document.getElementById('no-history-msg').style.display='block'" class="w-full px-3 py-2 bg-red-50 text-red-600 text-sm rounded-lg hover:bg-red-100">Remove</button></div>
        </div>`);
}
</script>
@endpush
@endsection
