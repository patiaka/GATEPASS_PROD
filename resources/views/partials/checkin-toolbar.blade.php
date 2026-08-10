{{-- Barre unifiée recherche + filtres période + export (check-in véhicule & matériel) --}}
<div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center gap-3">

        {{-- Search --}}
        <div class="relative w-full lg:w-72">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="{{ $placeholder ?? 'Search…' }}"
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
        </div>

        {{-- Period segmented control --}}
        <div class="inline-flex items-center rounded-lg border border-gray-300 bg-gray-50 p-0.5 self-start lg:self-auto">
            @foreach (['all' => 'All', 'today' => 'Today', '24h' => '24h', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                <button type="button" wire:click="setPeriod('{{ $key }}')" @class([
                    'px-3 py-1.5 rounded-md text-xs font-medium transition whitespace-nowrap',
                    'bg-[#134169] text-white shadow-sm' => $period === $key,
                    'text-slate-600 hover:text-slate-900' => $period !== $key,
                ])>{{ $label }}</button>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 lg:ml-auto">
            @if ($this->search !== '' || $period !== 'all')
                <button wire:click="ResetFilter"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-slate-600 text-sm hover:bg-slate-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Clear
                </button>
            @endif

            <button wire:click="export" wire:loading.attr="disabled" wire:target="export"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#0e3a61] text-white text-sm font-medium hover:bg-[#0c3253] shadow-sm transition disabled:opacity-60">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                </svg>
                <span wire:loading.remove wire:target="export">Export</span>
                <span wire:loading wire:target="export">Exporting…</span>
            </button>
        </div>
    </div>
</div>
