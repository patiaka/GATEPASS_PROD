<div class="p-3 sm:p-5">

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="text-xl sm:text-2xl font-bold text-[#134169]">{{ __('Material gate console') }}</h1>
        <p class="text-sm text-slate-500">{{ __('Guard post — record entries & exits') }}</p>
    </div>

    {{-- Search --}}
    <div class="relative mb-5">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
        </span>
        <input type="text" wire:model.live.debounce.300ms="search" inputmode="search"
            placeholder="{{ __('Search a material — reference, company, requester…') }}"
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
            <div wire:key="mgc-{{ $r->id }}" class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 flex flex-col">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        {{-- Référence en gros caractères : identifiant principal pour le gardien --}}
                        <p class="text-2xl sm:text-3xl font-extrabold text-[#134169] tracking-tight leading-none truncate">
                            {{ $r->reference }}
                        </p>
                        @if ($r->company)
                            <p class="text-xs text-slate-500 mt-1 truncate">{{ $r->company }}</p>
                        @endif
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

                {{-- Département du demandeur --}}
                @if ($r->user?->department?->name)
                    <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium self-start">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-8a4 4 0 014-4h10a4 4 0 014 4v8M7 21v-4h10v4M7 10V7a4 4 0 018 0v3" />
                        </svg>
                        {{ $r->user->department->name }}
                    </span>
                @endif

                <p class="text-xs text-slate-500 mt-1.5 truncate">
                    {{ __('Requester') }}: {{ $r->user?->name ?? '—' }}
                </p>
                @if ($r->person_out?->name || $r->person_out_name)
                    <p class="text-xs text-slate-500 truncate">
                        {{ __('Delegated Person') }}: {{ $r->person_out?->name ?? $r->person_out_name }}
                    </p>
                @endif

                {{-- Gros bouton d'action (mouvement opposé au dernier) --}}
                {{-- Ouvre le formulaire de check-in pré-rempli (le mouvement inverse y est présélectionné) --}}
                <a href="{{ route('material.check_create', ['request' => $r->id]) }}"
                    @class([
                        'mt-4 h-14 rounded-xl text-base font-bold text-white flex items-center justify-center gap-2 shadow-sm transition',
                        'bg-emerald-600 hover:bg-emerald-700' => $isOut,
                        'bg-amber-600 hover:bg-amber-700' => ! $isOut,
                    ])>
                    @if ($isOut)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14" /></svg>
                        {{ __('Record ENTRY') }}
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        {{ __('Record EXIT') }}
                    @endif
                    <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
                <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-sm">{{ __('No approved material found.') }}</p>
            </div>
        @endforelse
    </div>
</div>
