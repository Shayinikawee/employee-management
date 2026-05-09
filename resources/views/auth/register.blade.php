@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold mb-6">Employee Registration</h1>
        <p class="text-gray-600 mb-6">Complete your registration. Your information will be reviewed by admin before you can access your account.</p>

        <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- PF Number & NIC (Row 1) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="pf_number" class="block text-sm font-medium text-gray-700 mb-2">PF Number *</label>
                    <input 
                        type="text" 
                        id="pf_number" 
                        name="pf_number" 
                        value="{{ old('pf_number') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pf_number') border-red-500 @enderror"
                        placeholder="e.g., PF001234"
                        required
                    >
                    @error('pf_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nic" class="block text-sm font-medium text-gray-700 mb-2">NIC/ID Number *</label>
                    <input 
                        type="text" 
                        id="nic" 
                        name="nic" 
                        value="{{ old('nic') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nic') border-red-500 @enderror"
                        placeholder="e.g., 123456789V"
                        required
                    >
                    @error('nic')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Full Name & Email (Row 2) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="Your full name"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="your@email.com"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Date of Birth & Grade (Row 3) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                    <input 
                        type="date" 
                        id="date_of_birth" 
                        name="date_of_birth" 
                        value="{{ old('date_of_birth') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror"
                        required
                    >
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">Grade *</label>
                    <input 
                        type="text" 
                        id="grade" 
                        name="grade" 
                        value="{{ old('grade') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('grade') border-red-500 @enderror"
                        placeholder="e.g., Grade 1"
                        required
                    >
                    @error('grade')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Designation & Unit (Row 4) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="current_designation" class="block text-sm font-medium text-gray-700 mb-2">Current Designation *</label>
                    <input 
                        type="text" 
                        id="current_designation" 
                        name="current_designation" 
                        value="{{ old('current_designation') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_designation') border-red-500 @enderror"
                        placeholder="Your position"
                        required
                    >
                    @error('current_designation')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-2">Unit/Department *</label>
                    <select 
                        id="unit_id" 
                        name="unit_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('unit_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">-- Select Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" @selected(old('unit_id') == $unit->id)>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Number & Address (Row 5) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">Contact Number *</label>
                    <input 
                        type="tel" 
                        id="contact_number" 
                        name="contact_number" 
                        value="{{ old('contact_number') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_number') border-red-500 @enderror"
                        placeholder="+94..."
                        required
                    >
                    @error('contact_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        value="{{ old('address') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                        placeholder="Your address"
                    >
                    @error('address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password & Confirm Password (Row 6) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        placeholder="Minimum 8 characters"
                        required
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Re-enter your password"
                        required
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-6">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 underline">Already registered? Login here</a>
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    Register
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
