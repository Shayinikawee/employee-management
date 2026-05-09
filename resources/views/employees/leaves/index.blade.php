@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">My Leave Requests</h1>
            <p class="text-gray-600">View and manage your leave applications</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('employee-leaves.balances') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                View Balances
            </a>
            <a href="{{ route('employee-leaves.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Apply for Leave
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Status Filter -->
    <div class="mb-6 flex gap-4">
        <a href="{{ route('employee-leaves.index') }}" 
           class="px-4 py-2 rounded-lg {{ request('status') ? 'bg-gray-200' : 'bg-blue-600 text-white' }}">
            All
        </a>
        <a href="{{ route('employee-leaves.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg {{ request('status') === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Pending
        </a>
        <a href="{{ route('employee-leaves.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 rounded-lg {{ request('status') === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Approved
        </a>
        <a href="{{ route('employee-leaves.index', ['status' => 'rejected']) }}" 
           class="px-4 py-2 rounded-lg {{ request('status') === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Rejected
        </a>
    </div>

    @if($leaves->count())
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Leave Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">From - To</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Days</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Applied On</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($leaves as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $leave->leaveType->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $leave->date_from->format('M d, Y') }} - {{ $leave->date_to->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ $leave->number_of_days }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($leave->status === 'pending')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($leave->status === 'approved')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $leave->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('employee-leaves.show', $leave->id) }}" class="text-blue-600 hover:text-blue-800 underline">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $leaves->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 text-lg mb-4">No leave requests found.</p>
            <a href="{{ route('employee-leaves.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Apply for Leave
            </a>
        </div>
    @endif
</div>
@endsection
