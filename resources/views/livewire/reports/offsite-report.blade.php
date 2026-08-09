<div class="p-4 md:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-[#134169]">Offsite Records</h1>
            <p class="text-sm text-slate-500 mt-1">Vehicle exits analytics — busiest vehicles and departments</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Department filter --}}
            <select wire:model.live="department"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                <option value="">All departments</option>
                @foreach ($departments as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>

            {{-- Period pills --}}
            <div class="flex flex-wrap items-center gap-1.5">
                @foreach (['all' => 'All', 'today' => 'Today', '24h' => '24h', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                    <button type="button" wire:click="setPeriod('{{ $key }}')" @class([
                        'px-3 py-1.5 rounded-full text-xs font-medium border transition',
                        'bg-[#134169] text-white border-[#134169] shadow-sm' => $period === $key,
                        'bg-white text-slate-600 border-gray-300 hover:bg-slate-50' => $period !== $key,
                    ])>{{ $label }}</button>
                @endforeach
            </div>
        </div>
    </div>

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

    {{-- Ranked bars --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top vehicles --}}
        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-sm text-[#134169]">Top vehicles by exits</h2>
                <span class="text-xs text-slate-400">Top 10</span>
            </div>

            @php $maxV = $topVehicles->max('total') ?: 1; @endphp
            @forelse ($topVehicles as $row)
                <div class="flex items-center gap-3 py-1.5" title="{{ $row->label }} — {{ $row->total }} exits">
                    <span class="w-24 shrink-0 text-xs font-medium text-slate-600 truncate">{{ $row->label }}</span>
                    <div class="flex-1 h-4 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-4 rounded-full bg-[#134169]" style="width: {{ max(4, round($row->total / $maxV * 100)) }}%"></div>
                    </div>
                    <span class="w-8 text-right text-xs font-bold text-slate-700">{{ $row->total }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-400 italic py-6 text-center">No exit recorded for this filter.</p>
            @endforelse
        </section>

        {{-- By department --}}
        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-sm text-[#134169]">Exits by department</h2>
                <span class="text-xs text-slate-400">All departments</span>
            </div>

            @php $maxD = $byDepartment->max('total') ?: 1; @endphp
            @forelse ($byDepartment as $row)
                <div class="flex items-center gap-3 py-1.5" title="{{ $row->label }} — {{ $row->total }} exits">
                    <span class="w-28 shrink-0 text-xs font-medium text-slate-600 truncate">{{ $row->label }}</span>
                    <div class="flex-1 h-4 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-4 rounded-full bg-[#134169]" style="width: {{ max(4, round($row->total / $maxD * 100)) }}%"></div>
                    </div>
                    <span class="w-8 text-right text-xs font-bold text-slate-700">{{ $row->total }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-400 italic py-6 text-center">No exit recorded for this filter.</p>
            @endforelse
        </section>
    </div>

    {{-- Over time --}}
    <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-sm text-[#134169]">Exits over time</h2>
            <span class="text-xs text-slate-400">Daily{{ $period === 'all' ? ' — last 30 days' : '' }}</span>
        </div>

        @php $maxT = $overTime->max('total') ?: 1; @endphp
        @if ($overTime->isNotEmpty())
            <div class="flex items-end gap-1 h-44">
                @foreach ($overTime as $pt)
                    <div class="flex-1 flex flex-col justify-end h-full"
                        title="{{ \Illuminate\Support\Carbon::parse($pt->d)->format('d/m/Y') }} — {{ $pt->total }} exits">
                        <div class="w-full mx-auto max-w-[16px] rounded-t bg-[#134169] hover:bg-[#0e3a61] transition-colors"
                            style="height: {{ max(3, round($pt->total / $maxT * 100)) }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-2 text-[11px] text-slate-400">
                <span>{{ \Illuminate\Support\Carbon::parse($overTime->first()->d)->format('d/m/Y') }}</span>
                <span>{{ \Illuminate\Support\Carbon::parse($overTime->last()->d)->format('d/m/Y') }}</span>
            </div>
        @else
            <p class="text-sm text-slate-400 italic py-6 text-center">No exit recorded for this period.</p>
        @endif
    </section>
</div>
