<header class="flex justify-between items-center px-8 py-2 border-b border-gray-200 bg-gray-50">
    <!-- Bienvenue -->
    <div class="flex gap-4">
        <div class="flex flex-col">
            <span class="text-xs">Welcome,</span>
            <span class="font-medium text-md">{{ Auth::user()->name }}</span>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="flex flex-1 gap-3 justify-end">

        <!-- Quick Action avec peer (sans JS) -->
        <div class="relative flex justify-end px-2">
            <!-- Checkbox cachée -->
            <input type="checkbox" id="toggle-dropdown" class="peer hidden" />

            <!-- Bouton "Create New" -->
            <label for="toggle-dropdown"
                class="flex items-center gap-2 relative p-1 text-sm cursor-pointer select-none">
                <span>Create New</span>
                <svg class="w-4 h-4 ml-auto text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </label>

            <!-- Menu déroulant -->
            <ul
                class="absolute top-full right-0 p-4 grid gap-2 bg-white mt-2 shadow-lg w-56 rounded z-50 text-sm text-slate-800 opacity-0 scale-95 peer-checked:opacity-100 peer-checked:scale-100 peer-checked:visible invisible transition-all duration-200">
                <li class="hover:bg-[#0e3a615d] hover:text-slate-50 rounded-lg">
                    <a href="{{ route('car.create') }}" class="py-2 px-4 flex items-center gap-2">
                        <!-- Icône écriture / formulaire -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" />
                        </svg>
                        Vehicle offsite
                    </a>
                </li>
                <li class="hover:bg-[#0e3a615d] hover:text-slate-50 rounded-lg">
                    <a href="{{ route('material.create') }}" class="py-2 px-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" />
                        </svg>
                        Material offsite
                    </a>
                </li>
                @if (Auth::user()->isAdmin())
                    <li class="hover:bg-[#0e3a615d] hover:text-slate-50 rounded-lg">
                        <a href="{{ route('user.create') }}" class="py-2 px-4 flex items-center gap-2">
                            <!-- Icône utilisateur -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            User
                        </a>
                    </li>
                    <li class="hover:bg-[#0e3a615d] hover:text-slate-50 rounded-lg">
                        <a href="{{ route('department.create') }}" class="py-2 px-4 flex items-center gap-2">
                            <!-- Icône bâtiment / département -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 21v-8a4 4 0 014-4h10a4 4 0 014 4v8M7 21v-4h10v4M7 10V7a4 4 0 018 0v3" />
                            </svg>
                            Department
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Notifications : véhicules actuellement dehors (Admin / GM / Security) -->
        @php
            $canSeeVehiclesOut = Auth::user()->isAdmin() || Auth::user()->isGm() || Auth::user()->isSecurity();
            $vehiclesOut = $canSeeVehiclesOut
                ? \App\Models\Recording::query()->vehiclesOut()->with('requestable')->get()
                : collect();
        @endphp

        <div class="relative flex justify-end text-slate-800" x-data="{ open: false }">
            <button type="button" @click="open = !open"
                class="relative bg-white border rounded-md p-1 shadow-sm hover:bg-slate-50 transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if ($vehiclesOut->count() > 0)
                    <span class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1
                                 text-[10px] font-bold text-white bg-amber-500 rounded-full ring-2 ring-gray-50">
                        {{ $vehiclesOut->count() }}
                    </span>
                @endif
            </button>

            <!-- Dropdown notification -->
            <div x-show="open" x-cloak x-transition @click.outside="open = false"
                class="absolute top-full right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-50 border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2.5 border-b bg-amber-50/60">
                    <span class="font-semibold text-sm text-[#134169]">Vehicles currently out</span>
                    <span class="text-xs font-medium text-amber-700 bg-amber-100 rounded-full px-2 py-0.5">{{ $vehiclesOut->count() }}</span>
                </div>

                <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                    @forelse ($vehiclesOut as $rec)
                        <a href="{{ route('car.check') }}" class="block px-4 py-2.5 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-semibold text-[#134169] text-sm">#{{ $rec->requestable?->reference }}</span>
                                <span class="text-[11px] text-slate-400 whitespace-nowrap" title="{{ $rec->created_at?->format('d/m/Y H:i') }}">
                                    out {{ $rec->created_at?->diffForHumans() }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $rec->requestable?->car_number ?: '—' }}
                                @if ($rec->requestable?->company)
                                    · {{ $rec->requestable->company }}
                                @endif
                                <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px]">{{ $rec->gate }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mb-2 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <p class="text-sm">All vehicles are on site</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Réglages -->
        <a href="#" class="bg-white text-slate-800 border rounded-md p-1 shadow-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33h.01a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51h.01a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.01z" />
            </svg>
        </a>
    </div>
</header>