@extends('layouts.app')
@section('title', 'Add Unit')
@section('page-title', 'Add New Unit')

@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('units.store') }}">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Unit Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all">Save Unit</button>
            <a href="{{ route('units.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
