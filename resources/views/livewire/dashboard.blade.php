<div>
    <!-- Progress bar -->
    <div class="grid grid-cols-5 mb-6 p-0.5 bg-white gap-0.5 rounded-sm overflow-hidden shadow-sm">
        <div class="bg-yellow-300 h-3 w-full col-span-1"></div>
        <div class="bg-[#134169] h-3 w-full col-span-4"></div>
    </div>

    <!-- Dashboard Title -->
    <div>
        {{-- <h1 class="font-medium text-2xl text-slate-800">Dashboard</h1> --}}
        <h1 class="font-medium text-xl">Dashboard</h1>
    </div>

    <!-- Overview section -->
    <div class="rounded-2xl mt-4 p-4 col-span-12 grid grid-cols-12 gap-4 md:gap-5 border border-gray-200">
        {{-- <h2 class="text-xl font-semibold text-slate-800 col-span-12">Overview</h2> --}}
        <h2>Overview</h2>

        @php
            // Seuls Admin et Security voient les cartes "Checked out".
            // Sans elles : 4 colonnes -> ligne 1 = Gatepass, ligne 2 = Material.
            $canSeeCheckouts = Auth::user()->isAdmin() || Auth::user()->isSecurity();
        @endphp
        <div
            class="col-span-12 grid grid-cols-1 gap-4 md:gap-5 xl:grid-cols-4 {{ $canSeeCheckouts ? '2xl:grid-cols-5' : '' }}">
            <!-- ========================= Gatepass Cards ========================= -->
            <!-- All -->
            <a wire:navigate href="{{ route('car.index') }}"
                    class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                    <div class="flex gap-3 items-center">
                        <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 uppercase">All</h3>
                            <p class="text-xs text-slate-500">Gate pass Requests</p>
                        </div>
                    </div>
                    <span class="text-xl font-semibold text-slate-800">{{ $car_request_all }}</span>
                </a>

                <!-- Gatepass Checked Out -->
                @if ($canSeeCheckouts)
                <a wire:navigate href="{{ route('car.check') }}"
                    class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                    <div class="flex gap-3 items-center">
                        <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 uppercase">Gatepass</h3>
                            <p class="text-xs text-slate-500">Checked out Gatepass</p>
                        </div>
                    </div>
                    <span class="text-xl font-semibold text-slate-800">{{ $car_check_out }}</span>
                </a>
                @endif

                <!-- Approved -->
                <a wire:navigate href="{{ route('car.index', ['by_status' => 'Approved']) }}"
                    class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                    <div class="flex gap-3 items-center">
                        <span
                            class="bg-[#134169] text-green-300 rounded-full h-12 w-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M9 16.17l-3.88-3.88L4 13.41l5 5 12-12-1.41-1.41z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 uppercase">Gatepass Approved</h3>
                            <p class="text-xs text-slate-500">Approved Requests</p>
                        </div>
                    </div>
                    <span class="text-xl font-semibold text-slate-800">{{ $car_request_approved }}</span>
                </a>

                <!-- Pending -->
                <a wire:navigate
                    href="{{ Auth::user()->isApprover() ? route('car.pending') : route('car.index', ['by_status' => 'Pending']) }}"
                    class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                    <div class="flex gap-3 items-center">
                        <span
                            class="bg-[#134169] text-yellow-300 rounded-full h-12 w-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 4a8 8 0 100 16 8 8 0 000-16zm.5 9H12a.5.5 0 01-.5-.5V8H11v5a1 1 0 001 1h1v-1z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 uppercase">Gatepass Pending</h3>
                            <p class="text-xs text-slate-500">Pending Requests</p>
                        </div>
                    </div>
                    <span class="text-xl font-semibold text-slate-800">{{ $car_request_pending }}</span>
                </a>

                <!-- Rejected -->
                <a wire:navigate href="{{ route('car.index', ['by_status' => 'Rejected']) }}"
                    class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                    <div class="flex gap-3 items-center">
                        <span class="bg-[#134169] text-red-300 rounded-full h-12 w-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12c0 5.52 4.48 10 10 10s10-4.48 10-10c0-5.52-4.48-10-10-10zm5 13.59L8.41 7 7 8.41 15.59 17 17 15.59z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 uppercase">Gatepass Rejected</h3>
                            <p class="text-xs text-slate-500">Rejected Requests</p>
                        </div>
                    </div>
                    <span class="text-xl font-semibold text-slate-800">{{ $car_request_rejected }}</span>
                </a>

                <!-- ========================= Material Cards ========================= -->

                <!-- All -->
                <a wire:navigate href="{{ route('material.index') }}"
                    class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                    <div class="flex gap-3 items-center">
                        <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 uppercase">All</h3>
                            <p class="text-xs text-slate-500">Material Requests</p>
                        </div>
                    </div>
                    <span class="text-xl font-semibold text-slate-800">{{ $mat_request_all }}</span>
                </a>

                <!-- Material Checked Out -->
                @if ($canSeeCheckouts)
                <a wire:navigate href="{{ route('material.check') }}"
                    class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                    <div class="flex gap-3 items-center">
                        <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 uppercase">Material</h3>
                            <p class="text-xs text-slate-500">Checked out Material</p>
                        </div>
                    </div>
                    <span class="text-xl font-semibold text-slate-800">{{ $mat_check_out }}</span>
                </a>
            @endif

            <!-- Approved -->
            <a wire:navigate href="{{ route('material.index', ['by_status' => 'Approved']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-green-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17l-3.88-3.88L4 13.41l5 5 12-12-1.41-1.41z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Material Approved</h3>
                        <p class="text-xs text-slate-500">Approved Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $mat_request_approved }}</span>
            </a>

            <!-- Pending -->
            <a wire:navigate
                href="{{ Auth::user()->isApprover() ? route('material.pending') : route('material.index', ['by_status' => 'Pending']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-yellow-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 4a8 8 0 100 16 8 8 0 000-16zm.5 9H12a.5.5 0 01-.5-.5V8H11v5a1 1 0 001 1h1v-1z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Material Pending</h3>
                        <p class="text-xs text-slate-500">Pending Material Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $mat_request_pending }}</span>
            </a>

            <!-- Rejected -->
            <a wire:navigate href="{{ route('material.index', ['by_status' => 'Rejected']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-red-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12c0 5.52 4.48 10 10 10s10-4.48 10-10c0-5.52-4.48-10-10-10zm5 13.59L8.41 7 7 8.41 15.59 17 17 15.59z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Material Rejected</h3>
                        <p class="text-xs text-slate-500">Rejected Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $mat_request_rejected }}</span>
            </a>
        </div>


    </div>
    {{-- ========================= Statistics (visible par tous, limité au périmètre) ========================= --}}
    <div class="mt-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h2 class="font-semibold text-base text-[#134169]">Statistics</h2>

            {{-- Filtres --}}
            <div class="flex flex-wrap items-center gap-2">
                <select wire:model.live="stat_period"
                    class="text-xs rounded-lg border border-gray-300 bg-white px-3 py-1.5 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                    <option value="7">7 days</option>
                    <option value="14">14 days</option>
                    <option value="30">30 days</option>
                </select>

                <select wire:model.live="stat_type"
                    class="text-xs rounded-lg border border-gray-300 bg-white px-3 py-1.5 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                    <option value="all">All types</option>
                    <option value="material">Material</option>
                    <option value="vehicle">Vehicle</option>
                </select>

                @if ($canFilterDepartment)
                    <select wire:model.live="stat_department"
                        class="text-xs rounded-lg border border-gray-300 bg-white px-3 py-1.5 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none max-w-[180px]">
                        <option value="">All departments</option>
                        @foreach ($filterDepartments as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Traffic by gate --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Traffic by gate</h3>
                @php $gateMax = max($gate_traffic->max() ?: 0, 1); $gateTotal = $gate_traffic->sum(); @endphp
                <div class="space-y-4">
                    @foreach ($gate_traffic as $gate => $count)
                        <div>
                            <div class="flex items-baseline justify-between mb-1">
                                <span class="text-xs font-medium text-slate-600">{{ $gate }}</span>
                                <span class="text-xs text-slate-500">
                                    {{ $count }}@if ($gateTotal > 0)<span class="text-slate-400"> · {{ round($count / $gateTotal * 100) }}%</span>@endif
                                </span>
                            </div>
                            <div class="h-2.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-[#134169] transition-all"
                                    style="width: {{ $count > 0 ? max($count / $gateMax * 100, 3) : 0 }}%"
                                    title="{{ $gate }} : {{ $count }} passages"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Requests by department --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Requests by department <span class="text-[10px] font-normal text-slate-400">· all time</span></h3>
                @php $deptMax = max($dept_requests->max() ?: 0, 1); @endphp
                @if ($dept_requests->isEmpty())
                    <p class="text-sm text-slate-400">No data for this period</p>
                @else
                    <div class="space-y-4">
                        @foreach ($dept_requests as $dept => $count)
                            <div>
                                <div class="flex items-baseline justify-between mb-1 gap-2">
                                    <span class="text-xs font-medium text-slate-600 truncate" title="{{ $dept }}">{{ $dept }}</span>
                                    <span class="text-xs text-slate-500 shrink-0">{{ $count }}</span>
                                </div>
                                <div class="h-2.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full bg-[#134169] transition-all"
                                        style="width: {{ max($count / $deptMax * 100, 3) }}%"
                                        title="{{ $dept }} : {{ $count }} demandes"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Gate traffic over time --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-5 mt-6">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Gate traffic — last {{ $this->stat_period }} days</h3>
            @php
                $dailyMax = max(collect($daily_traffic)->max('total') ?: 0, 1);
                $dailyTotal = collect($daily_traffic)->sum('total');
            @endphp
            @if ($dailyTotal === 0)
                <p class="text-sm text-slate-400">No movements in the last {{ $this->stat_period }} days</p>
            @else
                <div class="flex items-end gap-1" style="height: 160px;">
                    @foreach ($daily_traffic as $d)
                        <div class="flex-1 h-full flex flex-col items-center justify-end group">
                            <span class="text-[10px] font-medium text-slate-600 mb-1 {{ $d['total'] === 0 ? 'invisible' : '' }}">{{ $d['total'] }}</span>
                            <div class="w-full rounded-t bg-[#134169] group-hover:bg-[#0e3457] transition-colors"
                                style="height: {{ $d['total'] > 0 ? max($d['total'] / $dailyMax * 90, 2) : 0 }}%"
                                title="{{ $d['date'] }} : {{ $d['total'] }} passages"></div>
                        </div>
                    @endforeach
                </div>
                <div class="flex gap-1 mt-1.5">
                    @foreach ($daily_traffic as $d)
                        <span class="flex-1 text-center text-[9px] text-slate-400 whitespace-nowrap overflow-hidden">{{ $d['label'] }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- ========================= Vehicles currently out ========================= -->
    @if (Auth::user()->isGm() || Auth::user()->isDirector() || Auth::user()->isHod() || Auth::user()->isAdmin() || Auth::user()->isSecurity())

        <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden mt-8">
            <div class="flex justify-between items-center border-b bg-amber-50/60 px-5 py-3">
                <h1 class="font-semibold text-base text-[#134169] flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8m-8 5h5m-9 4V6a2 2 0 012-2h10a2 2 0 012 2v13l-3-2-3 2-3-2-3 2z" />
                        </svg>
                    </span>
                    Vehicles currently out
                    <span class="text-xs font-medium text-amber-700 bg-amber-100 rounded-full px-2 py-0.5">{{ $vehicles_out->count() }}</span>
                </h1>
                <a href="{{ route('car.check') }}"
                    class="text-xs text-[#134169] border border-[#134169] px-3 py-1 rounded-lg hover:bg-[#134169] hover:text-white transition">
                    Vehicle log
                </a>
            </div>

            @if ($vehicles_out->isEmpty())
                <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="text-sm">All vehicles are on site</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($vehicles_out as $rec)
                        <div class="flex flex-wrap items-center justify-between gap-2 px-5 py-3 hover:bg-slate-50/70 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="font-semibold text-[#134169] text-sm whitespace-nowrap">#{{ $rec->requestable?->reference }}</span>
                                <span class="text-sm font-medium text-slate-800 whitespace-nowrap">{{ $rec->requestable?->car_number ?: '—' }}</span>
                                <span class="text-xs text-slate-500 truncate">{{ $rec->requestable?->company }}</span>
                                @if ($rec->car_driver)
                                    <span class="text-xs text-slate-400 truncate">· {{ $rec->car_driver->name }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 ring-1 ring-slate-200">{{ $rec->gate }}</span>
                                <span title="{{ $rec->created_at?->format('d/m/Y H:i') }}">out {{ $rec->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- ========================= Latest Check-in / Check-out Tables ========================= -->
    @if (Auth::user()->isGm() || Auth::user()->isDirector() || Auth::user()->isHod() || Auth::user()->isAdmin() || Auth::user()->isSecurity())

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-8">

            <!-- ================= Gatepass Table ================= -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden">
                <div class="flex justify-between items-center border-b bg-slate-50 px-5 py-3">
                    <h1 class="font-semibold text-base text-[#134169]">
                        Latest Gatepass Check-in / Check-out
                    </h1>
                    <a href="{{ route('car.check') }}"
                        class="text-xs text-[#134169] border border-[#134169] px-3 py-1 rounded-lg
                      hover:bg-[#134169] hover:text-white transition">
                        Show All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-[12px]">
                        <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider">
                            <tr class="border-b border-gray-100">
                                <th class="px-3 py-2.5 text-left font-semibold">Ref</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Date</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Agent</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Vehicle</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Company</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Dept / Driver</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Gate</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($car_check_latest as $row)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-3 py-2.5 font-semibold text-[#134169] whitespace-nowrap">
                                        #{{ $row->requestable->reference }}
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-500 whitespace-nowrap" title="{{ $row->created_at?->format('d/m/Y H:i') }}">
                                        {{ $row->created_at?->diffForHumans() }}
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-700">
                                        {{ $row->user->name }}
                                    </td>
                                    <td class="px-3 py-2.5 font-medium text-slate-800 whitespace-nowrap">
                                        {{ $row->requestable->car_number ?: '—' }}
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-700">
                                        {{ $row->requestable->company }}
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-700">
                                        @if ($row->car_driver)
                                            <span class="text-slate-800">{{ $row->car_driver->name }}</span>
                                            <span class="block text-[10px] text-slate-400">{{ $row->car_driver->department?->name ?? '—' }}</span>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-600 ring-1 ring-slate-200">
                                            {{ $row->gate }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <span @class([
                                            'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold ring-1',
                                            'bg-emerald-50 text-emerald-700 ring-emerald-200' => $row->action === 'Entry',
                                            'bg-amber-50 text-amber-700 ring-amber-200' => $row->action === 'Exit',
                                            'bg-slate-100 text-slate-600 ring-slate-200' => ! in_array($row->action, ['Entry', 'Exit']),
                                        ])>
                                            <span @class([
                                                'w-1.5 h-1.5 rounded-full',
                                                'bg-emerald-500' => $row->action === 'Entry',
                                                'bg-amber-500' => $row->action === 'Exit',
                                                'bg-slate-400' => ! in_array($row->action, ['Entry', 'Exit']),
                                            ])></span>
                                            {{ $row->action }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-8 text-slate-400 text-sm">
                                        No recent movement
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================= Material Table ================= -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden">
                <div class="flex justify-between items-center border-b bg-slate-50 px-5 py-3">
                    <h1 class="font-semibold text-base text-[#134169]">
                        Latest Material Check-in / Check-out
                    </h1>
                    <a href="{{ route('material.check') }}"
                        class="text-xs text-[#134169] border border-[#134169] px-3 py-1 rounded-lg
                      hover:bg-[#134169] hover:text-white transition">
                        Show All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-[12px]">
                        <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider">
                            <tr class="border-b border-gray-100">
                                <th class="px-3 py-2.5 text-left font-semibold">Ref</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Date</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Department</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Agent</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Company</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($mat_check_latest as $row)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-3 py-2.5 font-semibold text-[#134169] whitespace-nowrap">
                                        #{{ $row->requestable->reference }}
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-500 whitespace-nowrap" title="{{ $row->created_at?->format('d/m/Y H:i') }}">
                                        {{ $row->created_at?->diffForHumans() }}
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-700">
                                        {{ $row->user->department?->name ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-700">
                                        {{ $row->user->name }}
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-700">
                                        {{ $row->requestable->company }}
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <span @class([
                                            'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold ring-1',
                                            'bg-emerald-50 text-emerald-700 ring-emerald-200' => $row->action === 'Entry',
                                            'bg-amber-50 text-amber-700 ring-amber-200' => $row->action === 'Exit',
                                            'bg-slate-100 text-slate-600 ring-slate-200' => ! in_array($row->action, ['Entry', 'Exit']),
                                        ])>
                                            <span @class([
                                                'w-1.5 h-1.5 rounded-full',
                                                'bg-emerald-500' => $row->action === 'Entry',
                                                'bg-amber-500' => $row->action === 'Exit',
                                                'bg-slate-400' => ! in_array($row->action, ['Entry', 'Exit']),
                                            ])></span>
                                            {{ $row->action }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-slate-400 text-sm">
                                        No recent movement
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endif




</div>
