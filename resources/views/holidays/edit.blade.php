@extends('layouts.app')
@section('title', 'Edit Holiday')
@section('page-title', 'Edit Holiday')

@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('holidays.update', $holiday) }}">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Holiday Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $holiday->name) }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Holiday Date <span class="text-red-500">*</span></label>
                    <input type="date" name="holiday_date" value="{{ old('holiday_date', $holiday->holiday_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                    @error('holiday_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Holiday Type <span class="text-red-500">*</span></label>
                    <select name="holiday_type" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                        @foreach(['Public Holiday','Mercantile Holiday','Special Holiday','Poya Day'] as $t)
                            <option value="{{ $t }}" {{ old('holiday_type', $holiday->holiday_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                    @error('holiday_type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all">Update Holiday</button>
            <a href="{{ route('holidays.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
