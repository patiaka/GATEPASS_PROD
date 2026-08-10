<div class="relative flex justify-end text-slate-800" x-data="{ open: false }">
    <button type="button" @click="open = !open" title="Notifications"
        class="relative bg-white border rounded-md p-1 shadow-sm hover:bg-slate-50 transition">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if ($badge > 0)
            <span class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1
                         text-[10px] font-bold text-white bg-amber-500 rounded-full ring-2 ring-gray-50">
                {{ $badge }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-cloak x-transition @click.outside="open = false"
        class="absolute top-full right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-50 border border-gray-100 overflow-hidden">

        <div class="flex items-center justify-between px-4 py-2.5 border-b bg-slate-50">
            <span class="font-semibold text-sm text-[#134169]">Notifications</span>
            @if ($badge > 0)
                <span class="text-xs font-medium text-amber-700 bg-amber-100 rounded-full px-2 py-0.5">{{ $badge }} to approve</span>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">

            {{-- 1) À approuver (selon l'étape) --}}
            @if ($awaiting->isNotEmpty())
                <p class="px-4 pt-3 pb-1 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Awaiting your approval</p>
                <div class="divide-y divide-gray-50">
                    @foreach ($awaiting as $item)
                        <a wire:navigate href="{{ $item->link }}" class="block px-4 py-2.5 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-semibold text-[#134169] text-sm">#{{ $item->ref }}</span>
                                <span class="text-[11px] text-slate-400 whitespace-nowrap">{{ $item->at?->diffForHumans() }}</span>
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-medium mr-1">{{ $item->type }}</span>
                                by {{ $item->who ?? '—' }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- 2) Décisions sur mes demandes --}}
            @if ($myDecisions->isNotEmpty())
                <p class="px-4 pt-3 pb-1 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Your requests — decisions</p>
                <div class="divide-y divide-gray-50">
                    @foreach ($myDecisions as $item)
                        <a wire:navigate href="{{ $item->link }}" class="block px-4 py-2.5 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-semibold text-[#134169] text-sm">#{{ $item->ref }}</span>
                                <span class="text-[11px] text-slate-400 whitespace-nowrap">{{ $item->at?->diffForHumans() }}</span>
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-medium mr-1">{{ $item->type }}</span>
                                <span @class([
                                    'inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold',
                                    'bg-emerald-50 text-emerald-700' => $item->status === 'Approved',
                                    'bg-rose-50 text-rose-700' => $item->status === 'Rejected',
                                ])>{{ $item->status }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- 3) Véhicules dehors (Admin / GM / Security) --}}
            @if ($vehiclesOut->isNotEmpty())
                <p class="px-4 pt-3 pb-1 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Vehicles currently out ({{ $vehiclesOut->count() }})</p>
                <div class="divide-y divide-gray-50">
                    @foreach ($vehiclesOut as $rec)
                        <a wire:navigate href="{{ route('car.check') }}" class="block px-4 py-2.5 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-semibold text-[#134169] text-sm">#{{ $rec->requestable?->reference }}</span>
                                <span class="text-[11px] text-slate-400 whitespace-nowrap">out {{ $rec->created_at?->diffForHumans() }}</span>
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $rec->requestable?->car_number ?: '—' }}
                                @if ($rec->requestable?->company) · {{ $rec->requestable->company }} @endif
                                <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px]">{{ $rec->gate }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- État vide --}}
            @if ($awaiting->isEmpty() && $myDecisions->isEmpty() && $vehiclesOut->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                    <svg class="w-8 h-8 mb-2 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="text-sm">You're all caught up</p>
                </div>
            @endif
        </div>
    </div>
</div>
