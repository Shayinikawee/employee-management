@extends('layouts.app')
@section('title', 'Import Attendance')
@section('page-title', 'Import Attendance Data')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <h3 class="text-base font-semibold text-slate-800 mb-2">Upload Fingerprint Data</h3>
        <p class="text-sm text-slate-500 mb-4">Upload a CSV file with columns: <code class="bg-slate-100 px-1 py-0.5 rounded text-xs">pf_number, date, check_in_time, check_out_time</code></p>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('attendance.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Select CSV/Excel File <span class="text-red-500">*</span></label>
                <input type="file" name="file" accept=".csv,.xlsx,.xls,.txt" required
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
            </div>
            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all">Import Attendance</button>
        </form>
    </div>

    {{-- Sample Format --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-3">Sample CSV Format</h3>
        <div class="bg-slate-900 text-slate-300 rounded-xl p-4 text-xs font-mono overflow-x-auto">
            <p>pf_number,date,check_in_time,check_out_time</p>
            <p>PF001,2025-01-15,08:00,17:00</p>
            <p>PF002,2025-01-15,08:35,17:15</p>
            <p>PF003,2025-01-15,,</p>
        </div>
        <p class="text-xs text-slate-400 mt-2">Empty check-in/out = absent. Check-in after 08:30 = late.</p>
    </div>
</div>
@endsection
