<div class="p-4 md:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-[#134169]">Offsite Records</h1>
            <p class="text-sm text-slate-500 mt-1">Check-out analytics — busiest companies, vehicles and departments</p>
        </div>

        {{-- Export buttons --}}
        <div class="flex items-center gap-2">
            <button wire:click="exportExcel" wire:loading.attr="disabled" wire:target="exportExcel"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 shadow-sm transition disabled:opacity-60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                </svg>
                <span wire:loading.remove wire:target="exportExcel">Excel</span>
                <span wire:loading wire:target="exportExcel">…</span>
            </button>
            <button wire:click="exportPdf" wire:loading.attr="disabled" wire:target="exportPdf"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#0e3a61] text-white text-sm font-medium hover:bg-[#0c3253] shadow-sm transition disabled:opacity-60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12" />
                </svg>
                <span wire:loading.remove wire:target="exportPdf">PDF</span>
                <span wire:loading wire:target="exportPdf">…</span>
            </button>
        </div>
    </div>

    {{-- Filters bar --}}
    <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
            <select wire:model.live="department"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                <option value="">All departments</option>
                @foreach ($departments as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="gate"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                <option value="">All gates</option>
                @foreach (['Front', 'Back', 'Airport'] as $g)
                    <option value="{{ $g }}">{{ $g }} gate</option>
                @endforeach
            </select>

            <div class="inline-flex items-center rounded-lg border border-gray-300 bg-gray-50 p-0.5 self-start lg:self-auto lg:ml-auto">
                @foreach (['all' => 'All', 'today' => 'Today', '24h' => '24h', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                    <button type="button" wire:click="setPeriod('{{ $key }}')" @class([
                        'px-3 py-1.5 rounded-md text-xs font-medium transition whitespace-nowrap',
                        'bg-[#134169] text-white shadow-sm' => $period === $key,
                        'text-slate-600 hover:text-slate-900' => $period !== $key,
                    ])>{{ $label }}</button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="flex gap-1 -mb-px">
            @foreach (['overview' => 'Overview', 'checkouts' => 'Check-outs', 'requests' => 'Requests'] as $key => $label)
                <button type="button" wire:click="setTab('{{ $key }}')" @class([
                    'px-4 py-2.5 text-sm font-medium border-b-2 transition',
                    'border-[#134169] text-[#134169]' => $tab === $key,
                    'border-transparent text-slate-500 hover:text-slate-800 hover:border-gray-300' => $tab !== $key,
                ])>{{ $label }}</button>
            @endforeach
        </nav>
    </div>

    {{-- ============================ OVERVIEW ============================ --}}
    @if ($tab === 'overview')
        {{-- KPI tiles --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $tiles = [
                    ['Exits', $exitsCount, 'text-amber-600 bg-amber-50', 'M17 8l4 4m0 0l-4 4m4-4H3'],
                    ['Entries', $entriesCount, 'text-emerald-600 bg-emerald-50', 'M11 16l-4-4m0 0l4-4m-4 4h18'],
                    ['Currently out', $currentlyOut, 'text-[#134169] bg-blue-50', 'M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['Distinct vehicles', $distinctVehicles, 'text-indigo-600 bg-indigo-50', 'M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z'],
                ];
            @endphp
            @foreach ($tiles as [$label, $value, $tone, $path])
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg {{ $tone }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-2xl font-bold text-slate-800 leading-none">{{ number_format($value) }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $label }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Exits over time (area chart) --}}
            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">Exits over time</h2>
                    <span class="text-xs text-slate-400">Daily{{ $period === 'all' ? ' — last 30 days' : '' }}</span>
                </div>

                @if ($overTime->isNotEmpty())
                    @php
                        $otN = $overTime->count();
                        $otMax = $overTime->max('total') ?: 1;
                        $padX = 12; $padTop = 14; $padBot = 10; $W = 620; $H = 200;
                        $plotW = $W - 2 * $padX; $plotH = $H - $padTop - $padBot;
                        $pts = [];
                        foreach ($overTime->values() as $i => $pt) {
                            $x = $padX + ($otN <= 1 ? $plotW / 2 : $i / ($otN - 1) * $plotW);
                            $y = $padTop + $plotH - ($pt->total / $otMax) * $plotH;
                            $pts[] = [round($x, 1), round($y, 1)];
                        }
                        $line = collect($pts)->map(fn ($p) => $p[0] . ',' . $p[1])->implode(' ');
                        $base = $padTop + $plotH;
                        $area = $pts[0][0] . ',' . $base . ' ' . $line . ' ' . end($pts)[0] . ',' . $base;
                    @endphp
                    <svg viewBox="0 0 {{ $W }} {{ $H }}" style="width:100%;aspect-ratio:{{ $W }}/{{ $H }}">
                        <defs>
                            <linearGradient id="areaGrad" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#134169" stop-opacity="0.22" />
                                <stop offset="100%" stop-color="#134169" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        <polygon points="{{ $area }}" fill="url(#areaGrad)" />
                        <polyline points="{{ $line }}" fill="none" stroke="#134169" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" />
                        @foreach ($pts as $p)
                            <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="2.4" fill="#fff" stroke="#134169" stroke-width="1.5" />
                        @endforeach
                    </svg>
                    <div class="flex justify-between mt-1 text-[11px] text-slate-400">
                        <span>{{ \Illuminate\Support\Carbon::parse($overTime->first()->d)->format('d/m/Y') }}</span>
                        <span>Peak: {{ $otMax }}</span>
                        <span>{{ \Illuminate\Support\Carbon::parse($overTime->last()->d)->format('d/m/Y') }}</span>
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic py-10 text-center">No exit recorded for this period.</p>
                @endif
            </section>

            {{-- Exits by department (donut) --}}
            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <h2 class="font-semibold text-sm text-[#134169] mb-4">Exits share by department</h2>

                @if (!empty($deptDonut))
                    <div class="flex items-center gap-4">
                        <svg viewBox="0 0 42 42" class="w-28 h-28 shrink-0">
                            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#eef2f7" stroke-width="6" />
                            @php $cum = 0; @endphp
                            @foreach ($deptDonut as $seg)
                                <circle cx="21" cy="21" r="15.915" fill="none" stroke="{{ $seg['color'] }}" stroke-width="6"
                                    stroke-dasharray="{{ $seg['percent'] }} {{ 100 - $seg['percent'] }}"
                                    stroke-dashoffset="{{ 25 - $cum }}" />
                                @php $cum += $seg['percent']; @endphp
                            @endforeach
                            <text x="21" y="20.5" text-anchor="middle" font-size="5" font-weight="700" fill="#0f172a">{{ $exitsCount }}</text>
                            <text x="21" y="25.5" text-anchor="middle" font-size="2.8" fill="#64748b">exits</text>
                        </svg>
                        <ul class="flex-1 space-y-1.5 min-w-0">
                            @foreach ($deptDonut as $seg)
                                <li class="flex items-center gap-2 text-xs">
                                    <span class="w-2.5 h-2.5 rounded-sm shrink-0" style="background: {{ $seg['color'] }}"></span>
                                    <span class="text-slate-600 truncate flex-1">{{ $seg['label'] }}</span>
                                    <span class="text-slate-400">{{ $seg['percent'] }}%</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic py-10 text-center">No exit recorded for this filter.</p>
                @endif
            </section>
        </div>
    @endif

    {{-- ============================ CHECK-OUTS ============================ --}}
    @if ($tab === 'checkouts')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">Top vehicles by exits</h2>
                    <span class="text-xs text-slate-400">Top 10</span>
                </div>
                @include('livewire.reports.partials.bars', ['rows' => $topVehicles, 'color' => '#134169', 'unit' => 'exits', 'labelWidth' => 'w-24', 'empty' => 'No exit recorded for this filter.'])
            </section>

            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">Exits by department</h2>
                    <span class="text-xs text-slate-400">Vehicle + material</span>
                </div>
                @include('livewire.reports.partials.bars', ['rows' => $byDepartment, 'color' => '#134169', 'unit' => 'exits', 'labelWidth' => 'w-28', 'empty' => 'No exit recorded for this filter.'])
            </section>
        </div>

        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-sm text-[#134169]">Top companies by check-outs</h2>
                <span class="text-xs text-slate-400">Vehicle + material · Top 10</span>
            </div>
            @include('livewire.reports.partials.bars', ['rows' => $topCompanies, 'color' => '#134169', 'unit' => 'check-outs', 'labelWidth' => 'w-40', 'empty' => 'No check-out recorded for this filter.'])
        </section>
    @endif

    {{-- ============================ REQUESTS ============================ --}}
    @if ($tab === 'requests')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">Top companies by requests</h2>
                    <span class="text-xs text-slate-400">Vehicle + material · Top 10</span>
                </div>
                @include('livewire.reports.partials.bars', ['rows' => $topCompaniesReq, 'color' => '#059669', 'unit' => 'requests', 'labelWidth' => 'w-40', 'empty' => 'No request for this filter.'])
            </section>

            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">Requests by department</h2>
                    <span class="text-xs text-slate-400">Vehicle + material</span>
                </div>
                @include('livewire.reports.partials.bars', ['rows' => $byDepartmentReq, 'color' => '#059669', 'unit' => 'requests', 'labelWidth' => 'w-28', 'empty' => 'No request for this filter.'])
            </section>
        </div>
    @endif
</div>
