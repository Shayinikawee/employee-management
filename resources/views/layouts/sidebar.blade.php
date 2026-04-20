{{-- Sidebar Navigation --}}
<aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white z-40 transition-transform duration-300 flex flex-col">
    {{-- Brand Header --}}
    <div class="px-5 py-5 border-b border-slate-700/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h2 class="text-sm font-bold tracking-wide text-white">RDA Deniyaya</h2>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest">Employee Management</p>
            </div>
        </div>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @php
            $links = [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                ['route' => 'employees.index', 'label' => 'Employees', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                ['route' => 'units.index', 'label' => 'Units', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'],
                ['route' => 'attendance.index', 'label' => 'Attendance', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>'],
                ['route' => 'leaves.index', 'label' => 'Leave Management', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                ['route' => 'holidays.index', 'label' => 'Holidays', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>'],
                ['route' => 'leaves.balance', 'label' => 'Leave Balances', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
            ];
        @endphp

        @foreach($links as $link)
            @php
                $isActive = request()->routeIs($link['route'] . '*') || request()->routeIs($link['route']);
            @endphp
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ $isActive
                          ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-amber-400 shadow-sm'
                          : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-amber-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $link['icon'] !!}</svg>
                <span>{{ $link['label'] }}</span>
                @if($isActive)
                    <div class="ml-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- Sidebar Footer --}}
    <div class="p-4 border-t border-slate-700/50">
        <div class="bg-slate-800/50 rounded-xl p-3">
            <p class="text-[10px] text-slate-500 uppercase tracking-wider mb-1">System Info</p>
            <p class="text-xs text-slate-400">RDA EMS v1.0</p>
            <p class="text-[10px] text-slate-500">Laravel {{ app()->version() }}</p>
        </div>
    </div>
</aside>
