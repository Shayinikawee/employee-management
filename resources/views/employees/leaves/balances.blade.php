@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-8">
        <a href="{{ route('employee-leaves.index') }}" class="text-blue-600 hover:text-blue-800 underline mb-4 inline-block">
            ← Back to My Leaves
        </a>
        <h1 class="text-3xl font-bold">My Leave Balances</h1>
        <p class="text-gray-600">Current Year: {{ now()->year }}</p>
    </div>

    @if($leaveBalances->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($leaveBalances as $balance)
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $balance->leaveType->name }}</h3>
                    
                    <div class="space-y-3">
                        <!-- Total Days -->
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Total Allocated</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $balance->total_days }}</span>
                        </div>

                        <!-- Used Days -->
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Used Days</span>
                            <span class="text-lg font-semibold text-orange-600">{{ $balance->used_days }}</span>
                        </div>

                        <!-- Remaining Days -->
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-gray-600 font-medium">Remaining Days</span>
                            <span class="text-2xl font-bold text-green-600">{{ $balance->remaining_days }}</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-6">
                        <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div 
                                class="bg-blue-500 h-full transition-all" 
                                style="width: {{ ($balance->used_days / $balance->total_days) * 100 }}%"
                            ></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            {{ number_format(($balance->used_days / $balance->total_days) * 100, 0) }}% used
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary Card -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-6 border border-blue-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Total Leave Days</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ $leaveBalances->sum('total_days') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Used</p>
                    <p class="text-3xl font-bold text-orange-600">
                        {{ $leaveBalances->sum('used_days') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Remaining</p>
                    <p class="text-3xl font-bold text-green-600">
                        {{ $leaveBalances->sum('remaining_days') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="mt-8">
            <a href="{{ route('employee-leaves.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Apply for Leave
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 text-lg mb-4">No leave balances found for this year.</p>
            <a href="{{ route('employee-leaves.index') }}" class="text-blue-600 hover:text-blue-800 underline">
                Go back to My Leaves
            </a>
        </div>
    @endif
</div>
@endsection
