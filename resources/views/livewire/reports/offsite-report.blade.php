@php
    $keys = array_keys($sources);
    $single = count($keys) === 1;
    // Hauteur des graphiques de classement : une ligne lisible par entrée.
    $barHeight = fn ($rows, int $min = 240) => max($min, ($rows instanceof \Countable ? count($rows) : $rows->count()) * ($single ? 30 : 42) + 40) . 'px';
    $filterKey = $period . $department . $gate . $scope;
    $share = fn (int $value, int $total) => $total > 0 ? round($value / $total * 100, 1) : 0;
@endphp

<div class="p-4 md:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-[#134169]">{{ __('Offsite Records') }}</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ __('Vehicle and material analytics — requests, check-outs and departments') }}
            </p>
        </div>

        <button type="button" wire:click="exportPdf" wire:loading.attr="disabled" wire:target="exportPdf"
            class="inline-flex items-center gap-2 rounded-lg bg-[#134169] px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-[#0e3252] disabled:cursor-wait disabled:opacity-60 self-start">
            <svg wire:loading.remove wire:target="exportPdf" class="w-4 h-4" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M4 6a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
            </svg>
            <svg wire:loading wire:target="exportPdf" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
            </svg>
            <span wire:loading.remove wire:target="exportPdf">{{ __('Export PDF') }}</span>
            <span wire:loading wire:target="exportPdf">{{ __('Generating…') }}</span>
        </button>
    </div>

    {{-- Filters bar --}}
    <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
            {{-- Vehicle / Material scope --}}
            <div class="inline-flex items-center rounded-lg border border-gray-300 bg-gray-50 p-0.5 self-start">
                @foreach (['all' => __('All'), 'vehicle' => __('Vehicle'), 'material' => __('Material')] as $key => $label)
                    <button type="button" wire:click="setScope('{{ $key }}')" @class([
                        'px-3 py-1.5 rounded-md text-xs font-medium transition whitespace-nowrap',
                        'bg-[#134169] text-white shadow-sm' => $scope === $key && $key !== 'material',
                        'bg-emerald-600 text-white shadow-sm' => $scope === $key && $key === 'material',
                        'text-slate-600 hover:text-slate-900' => $scope !== $key,
                    ])>{{ $label }}</button>
                @endforeach
            </div>

            <select wire:model.live="department"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                <option value="">{{ __('All departments') }}</option>
                @foreach ($departments as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="gate"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                <option value="">{{ __('All gates') }}</option>
                @foreach (['Front', 'Back', 'Airport'] as $g)
                    <option value="{{ $g }}">{{ __($g . ' gate') }}</option>
                @endforeach
            </select>

            <div class="inline-flex items-center rounded-lg border border-gray-300 bg-gray-50 p-0.5 self-start lg:self-auto lg:ml-auto">
                @foreach (['all' => __('All'), 'today' => __('Today'), '24h' => '24h', 'week' => __('Week'), 'month' => __('Month')] as $key => $label)
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
            @foreach (['overview' => __('Overview'), 'requests' => __('Requests'), 'checkouts' => __('Check-outs')] as $key => $label)
                <button type="button" wire:click="setTab('{{ $key }}')" @class([
                    'px-4 py-2.5 text-sm font-medium border-b-2 transition',
                    'border-[#134169] text-[#134169]' => $tab === $key,
                    'border-transparent text-slate-500 hover:text-slate-800 hover:border-gray-300' => $tab !== $key,
                ])>{{ $label }}</button>
            @endforeach
        </nav>
    </div>

    {{-- KPI tiles — communes à tous les onglets --}}
    @php
        $tiles = [
            ['requests', __('Requests submitted'), 'text-[#134169] bg-blue-50', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['exits', __('Check-outs'), 'text-amber-600 bg-amber-50', 'M17 8l4 4m0 0l-4 4m4-4H3'],
            ['entries', __('Check-ins'), 'text-emerald-600 bg-emerald-50', 'M11 16l-4-4m0 0l4-4m-4 4h18'],
            ['out', __('Currently out'), 'text-indigo-600 bg-indigo-50', 'M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($tiles as [$key, $label, $tone, $path])
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg {{ $tone }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-800 leading-none">{{ number_format($totals[$key]) }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $label }}</p>
                    </div>
                </div>
                @if (!$single)
                    <div class="mt-3 pt-2 border-t border-dashed border-gray-200 flex gap-4">
                        @foreach ($sources as $sKey => $s)
                            <span class="text-xs text-slate-600 inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-sm" style="background: {{ $s['color'] }}"></span>
                                {{ __($s['label']) }}
                                <b class="text-slate-800">{{ number_format($stats[$sKey][$key]) }}</b>
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ============================ OVERVIEW ============================ --}}
    @if ($tab === 'overview')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">{{ __('Check-outs over time') }}</h2>
                    <span class="text-xs text-slate-400">{{ $periodLabel }}</span>
                </div>
                @include('livewire.reports.partials.chart', [
                    'config' => $charts['overTime'],
                    'key' => 'overtime-' . $filterKey,
                    'height' => '280px',
                ])
            </section>

            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <h2 class="font-semibold text-sm text-[#134169] mb-4">
                    {{ $single ? __('Requests share by department') : __('Requests: Vehicle vs Material') }}
                </h2>
                @if ($totals['requests'] > 0)
                    @include('livewire.reports.partials.chart', [
                        'config' => $single ? $charts['deptDonut'] : $charts['typeSplit'],
                        'key' => 'donut-' . $filterKey,
                        'height' => '280px',
                    ])
                @else
                    <p class="text-sm text-slate-400 italic py-10 text-center">{{ __('No data for this filter.') }}</p>
                @endif
            </section>
        </div>

        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-sm text-[#134169]">{{ __('Requests submitted by department') }}</h2>
                <span class="text-xs text-slate-400">
                    {{ $single ? __($sources[$keys[0]]['label']) : __('Vehicle + Material') }}
                </span>
            </div>
            @if ($requestsByDept->isNotEmpty())
                @include('livewire.reports.partials.chart', [
                    'config' => $charts['requestsByDept'],
                    'key' => 'reqdept-' . $filterKey,
                    'height' => $barHeight($requestsByDept),
                ])
            @else
                <p class="text-sm text-slate-400 italic py-10 text-center">{{ __('No data for this filter.') }}</p>
            @endif
        </section>
    @endif

    {{-- ============================ REQUESTS ============================ --}}
    @if ($tab === 'requests')
        {{-- Chiffres par département --}}
        <section class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-sm text-[#134169]">{{ __('Requests submitted by department') }}</h2>
                <span class="text-xs text-slate-400">{{ $periodLabel }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="text-left font-semibold px-5 py-2.5">{{ __('Department') }}</th>
                            @foreach ($sources as $s)
                                <th class="text-right font-semibold px-5 py-2.5">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-sm" style="background: {{ $s['color'] }}"></span>
                                        {{ __($s['label']) }}
                                    </span>
                                </th>
                            @endforeach
                            <th class="text-right font-semibold px-5 py-2.5">{{ __('Total') }}</th>
                            <th class="text-right font-semibold px-5 py-2.5 w-40">{{ __('Share') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($requestsByDept as $row)
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-5 py-2.5 font-medium text-slate-700">{{ $row['label'] }}</td>
                                @foreach ($keys as $key)
                                    <td class="px-5 py-2.5 text-right text-slate-600">
                                        {{ number_format($row[$key]) }}</td>
                                @endforeach
                                <td class="px-5 py-2.5 text-right font-bold text-slate-800">
                                    {{ number_format($row['total']) }}</td>
                                <td class="px-5 py-2.5">
                                    <div class="flex items-center justify-end gap-2">
                                        <span
                                            class="text-xs text-slate-500 w-10 text-right">{{ $share($row['total'], $totals['requests']) }}%</span>
                                        <span class="h-1.5 w-20 rounded-full bg-slate-100 overflow-hidden">
                                            <span class="block h-1.5 rounded-full bg-[#134169]"
                                                style="width: {{ $share($row['total'], $totals['requests']) }}%"></span>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($keys) + 3 }}"
                                    class="px-5 py-8 text-center text-sm text-slate-400 italic">
                                    {{ __('No data for this filter.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($requestsByDept->isNotEmpty())
                        <tfoot class="bg-slate-50 font-semibold text-[#134169]">
                            <tr>
                                <td class="px-5 py-2.5">{{ __('Total') }}</td>
                                @foreach ($keys as $key)
                                    <td class="px-5 py-2.5 text-right">
                                        {{ number_format($stats[$key]['requests']) }}</td>
                                @endforeach
                                <td class="px-5 py-2.5 text-right">{{ number_format($totals['requests']) }}</td>
                                <td class="px-5 py-2.5 text-right">100%</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </section>

        {{-- Statuts par type --}}
        <section class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-sm text-[#134169]">{{ __('Requests by status') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="text-left font-semibold px-5 py-2.5">{{ __('Type') }}</th>
                            @foreach ($statusList as $status)
                                <th class="text-right font-semibold px-5 py-2.5">{{ __($status) }}</th>
                            @endforeach
                            <th class="text-right font-semibold px-5 py-2.5">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($sources as $key => $s)
                            <tr>
                                <td class="px-5 py-2.5">
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold text-white"
                                        style="background: {{ $s['color'] }}">{{ __($s['label']) }}</span>
                                </td>
                                @foreach ($statusList as $status)
                                    <td class="px-5 py-2.5 text-right text-slate-600">
                                        {{ number_format($statuses[$key][$status] ?? 0) }}</td>
                                @endforeach
                                <td class="px-5 py-2.5 text-right font-bold text-slate-800">
                                    {{ number_format($stats[$key]['requests']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">{{ __('Requests submitted by department') }}</h2>
                </div>
                @if ($requestsByDept->isNotEmpty())
                    @include('livewire.reports.partials.chart', [
                        'config' => $charts['requestsByDept'],
                        'key' => 'reqdept2-' . $filterKey,
                        'height' => $barHeight($requestsByDept, 300),
                    ])
                @else
                    <p class="text-sm text-slate-400 italic py-10 text-center">{{ __('No data for this filter.') }}</p>
                @endif
            </section>

            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">{{ __('Top companies by requests') }}</h2>
                    <span class="text-xs text-slate-400">{{ __('Top 10') }}</span>
                </div>
                @if ($requestsByCompany->isNotEmpty())
                    @include('livewire.reports.partials.chart', [
                        'config' => $charts['requestsByCompany'],
                        'key' => 'reqcompany-' . $filterKey,
                        'height' => $barHeight($requestsByCompany, 300),
                    ])
                @else
                    <p class="text-sm text-slate-400 italic py-10 text-center">{{ __('No data for this filter.') }}</p>
                @endif
            </section>
        </div>
    @endif

    {{-- ============================ CHECK-OUTS ============================ --}}
    @if ($tab === 'checkouts')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if (isset($sources['vehicle']))
                <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-sm text-[#134169]">
                            <span class="inline-block w-2 h-2 rounded-sm align-middle mr-1.5"
                                style="background: {{ $sources['vehicle']['color'] }}"></span>
                            {{ __('Top vehicles by check-outs') }}
                        </h2>
                        <span class="text-xs text-slate-400">
                            {{ number_format($distinctVehicles) }} {{ __('distinct vehicles') }}
                        </span>
                    </div>
                    @if ($topVehicles->isNotEmpty())
                        @include('livewire.reports.partials.chart', [
                            'config' => $charts['topVehicles'],
                            'key' => 'topvehicles-' . $filterKey,
                            'height' => '320px',
                        ])
                    @else
                        <p class="text-sm text-slate-400 italic py-10 text-center">{{ __('No data for this filter.') }}
                        </p>
                    @endif
                </section>
            @endif

            @if (isset($sources['material']))
                <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-sm text-emerald-700">
                            <span class="inline-block w-2 h-2 rounded-sm align-middle mr-1.5"
                                style="background: {{ $sources['material']['color'] }}"></span>
                            {{ __('Top items by quantity') }}
                        </h2>
                        <span class="text-xs text-slate-400">
                            {{ number_format($materialQuantity) }} {{ __('items requested') }}
                        </span>
                    </div>
                    @if ($topMaterials->isNotEmpty())
                        @include('livewire.reports.partials.chart', [
                            'config' => $charts['topMaterials'],
                            'key' => 'topmaterials-' . $filterKey,
                            'height' => '320px',
                        ])
                    @else
                        <p class="text-sm text-slate-400 italic py-10 text-center">{{ __('No data for this filter.') }}
                        </p>
                    @endif
                </section>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">{{ __('Check-outs by department') }}</h2>
                    <span class="text-xs text-slate-400">
                        {{ $single ? __($sources[$keys[0]]['label']) : __('Vehicle + Material') }}
                    </span>
                </div>
                @if ($exitsByDept->isNotEmpty())
                    @include('livewire.reports.partials.chart', [
                        'config' => $charts['exitsByDept'],
                        'key' => 'exitdept-' . $filterKey,
                        'height' => $barHeight($exitsByDept, 300),
                    ])
                @else
                    <p class="text-sm text-slate-400 italic py-10 text-center">{{ __('No data for this filter.') }}</p>
                @endif
            </section>

            <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-sm text-[#134169]">{{ __('Top companies by check-outs') }}</h2>
                    <span class="text-xs text-slate-400">{{ __('Top 10') }}</span>
                </div>
                @if ($exitsByCompany->isNotEmpty())
                    @include('livewire.reports.partials.chart', [
                        'config' => $charts['exitsByCompany'],
                        'key' => 'exitcompany-' . $filterKey,
                        'height' => $barHeight($exitsByCompany, 300),
                    ])
                @else
                    <p class="text-sm text-slate-400 italic py-10 text-center">{{ __('No data for this filter.') }}</p>
                @endif
            </section>
        </div>
    @endif
</div>
