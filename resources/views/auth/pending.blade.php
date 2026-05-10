@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-4">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold mb-4">Registration Pending</h1>

        <p class="text-gray-600 mb-6">
            Your registration has been submitted and is awaiting admin approval. 
            You will receive an email notification once your registration is reviewed.
        </p>

        @if($employee && $employee->rejection_reason)
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700 font-semibold mb-2">Registration Rejection Reason:</p>
                <p class="text-red-600">{{ $employee->rejection_reason }}</p>
            </div>
        @endif

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <p class="text-blue-700 mb-3">
                <strong>What happens next?</strong>
            </p>
            <ul class="text-left text-blue-600 space-y-2">
                <li>✓ Your registration is being reviewed by the admin</li>
                <li>✓ You will be notified via email ({{ auth()->user()->email }})</li>
                <li>✓ Once approved, you can access your profile and apply for leaves</li>
            </ul>
        </div>

        <div class="flex items-center justify-center gap-4">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
