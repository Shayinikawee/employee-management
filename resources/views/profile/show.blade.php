@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">My Profile</h1>
                <p class="text-gray-600">View your employee information</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Edit Profile
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <!-- Personal Information Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-6">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIC/ID Number</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->nic }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->date_of_birth?->format('M d, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->contact_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Employment Information Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-6">Employment Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">PF Number</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->pf_number }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->grade }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Designation</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->current_designation }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit/Department</label>
                    <p class="text-gray-900 font-semibold">{{ $employee->unit?->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Leave Balance Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-6">Leave Balance (Current Year)</h2>
            @if($employee->leaveBalances->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($employee->leaveBalances as $balance)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $balance->leaveType->name }}</h3>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>Total Allocated: <strong class="text-gray-900">{{ $balance->total_days }} days</strong></p>
                                <p>Used: <strong class="text-gray-900">{{ $balance->used_days }} days</strong></p>
                                <p>Remaining: <strong class="text-green-600 font-bold">{{ $balance->remaining_days }} days</strong></p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No leave balances available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
