<div class="p-3 sm:p-5">

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="text-xl sm:text-2xl font-bold text-[#134169]">{{ __('Gate console') }}</h1>
        <p class="text-sm text-slate-500">{{ __('Guard post — record entries & exits') }}</p>
    </div>

    {{-- Gate selector (gros boutons) --}}
    <div class="mb-3">
        <span class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">{{ __('Gate') }}</span>
        <div class="grid grid-cols-3 gap-2">
            @foreach (['Front', 'Back', 'Airport'] as $g)
                <button type="button" wire:click="setGate('{{ $g }}')" @class([
                    'h-12 rounded-xl text-sm sm:text-base font-semibold border-2 transition',
                    'bg-[#134169] text-white border-[#134169] shadow' => $gate === $g,
                    'bg-white text-slate-600 border-gray-200 hover:border-[#134169]/40' => $gate !== $g,
                ])>{{ __($g) }}</button>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <div class="relative mb-5">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
        </span>
        <input type="text" wire:model.live.debounce.300ms="search" inputmode="search"
            placeholder="{{ __('Search a vehicle — reference, plate, company, driver…') }}"
            class="w-full h-14 pl-12 pr-12 rounded-xl border border-gray-300 text-base focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
        @if ($search !== '')
            <button wire:click="clearSearch" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>

    {{-- Results --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4">
        @forelse ($results as $r)
            @php $isOut = $r->last_action === 'Exit'; @endphp
            <div wire:key="gc-{{ $r->id }}" class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 flex flex-col">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-lg font-bold text-[#134169] truncate">#{{ $r->reference }}</p>
                        <p class="text-sm text-slate-600 truncate">{{ $r->car_number ?: '—' }}@if ($r->company) · {{ $r->company }}@endif</p>
                    </div>
                    <span @class([
                        'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ring-1 whitespace-nowrap',
                        'bg-amber-50 text-amber-700 ring-amber-200' => $isOut,
                        'bg-emerald-50 text-emerald-700 ring-emerald-200' => ! $isOut,
                    ])>
                        <span @class(['w-1.5 h-1.5 rounded-full', 'bg-amber-500' => $isOut, 'bg-emerald-500' => ! $isOut])></span>
                        {{ $isOut ? __('Currently out') : __('On site') }}
                    </span>
                </div>

                @if ($r->car_drivers->isNotEmpty())
                    <p class="text-xs text-slate-500 mt-1.5 truncate">
                        {{ __('Driver') }}: {{ $r->car_drivers->map(fn ($d) => $d->user?->name)->filter()->join(', ') ?: '—' }}
                    </p>
                @endif

                {{-- Gros bouton d'action (mouvement opposé au dernier) --}}
                <button wire:click="record({{ $r->id }})" wire:loading.attr="disabled" wire:target="record({{ $r->id }})"
                    @class([
                        'mt-4 h-14 rounded-xl text-base font-bold text-white flex items-center justify-center gap-2 shadow-sm transition disabled:opacity-60',
                        'bg-emerald-600 hover:bg-emerald-700' => $isOut,
                        'bg-amber-600 hover:bg-amber-700' => ! $isOut,
                    ])>
                    <span wire:loading.remove wire:target="record({{ $r->id }})" class="inline-flex items-center gap-2">
                        @if ($isOut)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14" /></svg>
                            {{ __('Record ENTRY') }}
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            {{ __('Record EXIT') }}
                        @endif
                    </span>
                    <span wire:loading wire:target="record({{ $r->id }})" class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4" /><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" stroke-linecap="round" /></svg>
                        {{ __('Recording…') }}
                    </span>
                </button>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
                <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13l2-5a2 2 0 0 1 1.9-1.4h10.2A2 2 0 0 1 19 8l2 5m-18 0v4a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-4m-18 0h18" />
                </svg>
                <p class="text-sm">{{ __('No approved vehicle found.') }}</p>
            </div>
        @endforelse
    </div>
</div>
