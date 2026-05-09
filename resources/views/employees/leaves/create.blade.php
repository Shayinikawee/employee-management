@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Apply for Leave</h1>
            <p class="text-gray-600">Submit a new leave request</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700 font-semibold mb-2">Please fix the errors:</p>
                <ul class="text-red-600 text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-4">Your Leave Balances (Current Year)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @foreach($leaveBalances as $balance)
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <p class="font-medium text-gray-900">{{ $balance->leaveType->name }}</p>
                        <p class="text-sm text-gray-600 mt-2">
                            Remaining: <strong class="text-green-600">{{ $balance->remaining_days }} days</strong>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <form action="{{ route('employee-leaves.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf

            <!-- Leave Type -->
            <div class="mb-6">
                <label for="leave_type_id" class="block text-sm font-medium text-gray-700 mb-2">Leave Type *</label>
                <select 
                    id="leave_type_id" 
                    name="leave_type_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('leave_type_id') border-red-500 @enderror"
                    required
                    onchange="updateBalance()"
                >
                    <option value="">-- Select Leave Type --</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('leave_type_id') == $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('leave_type_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- From Date -->
            <div class="mb-6">
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date *</label>
                <input 
                    type="date" 
                    id="date_from" 
                    name="date_from" 
                    value="{{ old('date_from') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_from') border-red-500 @enderror"
                    required
                    onchange="calculateDays()"
                >
                @error('date_from')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- To Date -->
            <div class="mb-6">
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">To Date *</label>
                <input 
                    type="date" 
                    id="date_to" 
                    name="date_to" 
                    value="{{ old('date_to') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_to') border-red-500 @enderror"
                    required
                    onchange="calculateDays()"
                >
                @error('date_to')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Display Number of Days -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-gray-700">
                    Number of Days: <strong id="numberOfDays" class="text-blue-600">0 days</strong>
                </p>
            </div>

            <!-- Reason -->
            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Leave *</label>
                <textarea 
                    id="reason" 
                    name="reason" 
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reason') border-red-500 @enderror"
                    placeholder="Explain the reason for your leave"
                    required
                >{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4 pt-6">
                <a href="{{ route('employee-leaves.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateDays() {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;

    if (dateFrom && dateTo) {
        const from = new Date(dateFrom);
        const to = new Date(dateTo);
        const days = Math.floor((to - from) / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('numberOfDays').textContent = days + ' day' + (days > 1 ? 's' : '');
    }
}

function updateBalance() {
    // Could be used to dynamically update balance display
}
</script>
@endsection
