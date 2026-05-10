@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Employee Registration Requests</h1>
        <p class="text-gray-600">Review and approve/reject employee registrations</p>
    </div>

    <!-- Status Filter -->
    <div class="mb-6 flex gap-4">
        <a href="{{ route('registrations.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg {{ $status === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Pending
        </a>
        <a href="{{ route('registrations.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 rounded-lg {{ $status === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Approved
        </a>
        <a href="{{ route('registrations.index', ['status' => 'rejected']) }}" 
           class="px-4 py-2 rounded-lg {{ $status === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Rejected
        </a>
    </div>

    @if($employees->count())
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">PF Number</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Registration Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($employees as $employee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->pf_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($employee->approval_status === 'pending')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($employee->approval_status === 'approved')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm space-y-2">
                                <a href="#" onclick="showDetails({{ $employee->id }})" class="text-blue-600 hover:text-blue-800 underline">View Details</a>
                                
                                @if($employee->approval_status === 'pending')
                                    <div class="flex gap-2 mt-2">
                                        <form action="{{ route('registrations.approve', $employee->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition" onclick="return confirm('Approve this registration?')">
                                                Approve
                                            </button>
                                        </form>
                                        <button type="button" onclick="showRejectForm({{ $employee->id }})" class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition">
                                            Reject
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $employees->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 text-lg">No {{ $status }} registrations found.</p>
        </div>
    @endif
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
        <div class="p-6" id="detailsContent">
            <!-- Content loaded via JS -->
        </div>
        <div class="p-6 border-t flex justify-end gap-3">
            <button onclick="document.getElementById('detailsModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Reject Form Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Reject Registration</h3>
            <form id="rejectForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection *</label>
                    <textarea 
                        id="rejection_reason" 
                        name="rejection_reason" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Provide a reason for rejection..."
                        required
                    ></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showDetails(employeeId) {
    // In a real app, you'd fetch this data via AJAX
    // For now, redirect to a details page or show basic info
    alert('Employee ID: ' + employeeId);
}

function showRejectForm(employeeId) {
    document.getElementById('rejectForm').action = '/admin/registrations/' + employeeId + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}
</script>
@endsection
