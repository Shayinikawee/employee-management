@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('employee-leaves.index') }}" class="text-blue-600 hover:text-blue-800 underline mb-4 inline-block">
                ← Back to My Leaves
            </a>
            <h1 class="text-3xl font-bold">Leave Request Details</h1>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $leave->leaveType->name }}</h2>
                    <p class="text-gray-600 text-sm mt-1">Applied on {{ $leave->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <div>
                    @if($leave->status === 'pending')
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                    @elseif($leave->status === 'approved')
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                    @else
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                    @endif
                </div>
            </div>

            <!-- Leave Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <p class="text-gray-900 font-semibold">{{ $leave->date_from->format('M d, Y (l)') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <p class="text-gray-900 font-semibold">{{ $leave->date_to->format('M d, Y (l)') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Days</label>
                    <p class="text-gray-900 font-semibold text-lg text-blue-600">{{ $leave->number_of_days }} days</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                    <p class="text-gray-900 font-semibold">{{ $leave->leaveType->name }}</p>
                </div>
            </div>

            <!-- Reason -->
            <div class="mb-6 pb-6 border-b">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $leave->reason }}</p>
            </div>

            <!-- Approval Information (if processed) -->
            @if($leave->status !== 'pending')
                <div class="mb-6 pb-6 border-b">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $leave->status === 'approved' ? 'Approved By' : 'Rejected By' }}
                    </label>
                    <p class="text-gray-900 font-semibold">
                        {{ $leave->approvedByUser?->name ?? 'Admin' }}
                    </p>
                    @if($leave->approved_at)
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $leave->status === 'approved' ? 'Approved on' : 'Rejected on' }}: {{ $leave->approved_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    @endif
                </div>

                @if($leave->status === 'rejected' && $leave->rejection_reason)
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <label class="block text-sm font-medium text-red-700 mb-2">Rejection Reason</label>
                        <p class="text-red-600">{{ $leave->rejection_reason }}</p>
                    </div>
                @endif
            @endif
        </div>

        <!-- Action Button -->
        <div class="flex gap-4">
            <a href="{{ route('employee-leaves.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Back to List
            </a>
            @if($leave->status === 'pending')
                <p class="text-gray-600 text-sm flex items-center">
                    This request is pending admin review.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
