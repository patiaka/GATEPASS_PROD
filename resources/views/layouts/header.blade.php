<header class="flex justify-between items-center px-8 py-2 border-b border-gray-200 bg-gray-50">
    <!-- Bienvenue -->
    <div class="flex gap-4">
        <div class="flex flex-col">
            <span class="text-xs">{{ __('Welcome,') }}</span>
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
                <span>{{ __('Create New') }}</span>
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
                        {{ __('Vehicle offsite') }}
                    </a>
                </li>
                <li class="hover:bg-[#0e3a615d] hover:text-slate-50 rounded-lg">
                    <a href="{{ route('material.create') }}" class="py-2 px-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" />
                        </svg>
                        {{ __('Material offsite') }}
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
                            {{ __('User') }}
                        </a>
                    </li>
                    <li class="hover:bg-[#0e3a615d] hover:text-slate-50 rounded-lg">
                        <a href="{{ route('department.create') }}" class="py-2 px-4 flex items-center gap-2">
                            <!-- Icône bâtiment / département -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 21v-8a4 4 0 014-4h10a4 4 0 014 4v8M7 21v-4h10v4M7 10V7a4 4 0 018 0v3" />
                            </svg>
                            {{ __('Department') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Cloche de notifications (workflow d'approbation + décisions + véhicules dehors) -->
        <livewire:notification-bell />

        <!-- Sélecteur de langue FR / EN -->
        <div class="flex items-center rounded-md border bg-white shadow-sm overflow-hidden text-xs font-semibold">
            <a href="{{ route('locale.switch', 'fr') }}" @class([
                'px-2 py-1.5 transition',
                'bg-[#134169] text-white' => app()->getLocale() === 'fr',
                'text-slate-500 hover:bg-slate-50' => app()->getLocale() !== 'fr',
            ])>FR</a>
            <a href="{{ route('locale.switch', 'en') }}" @class([
                'px-2 py-1.5 transition',
                'bg-[#134169] text-white' => app()->getLocale() === 'en',
                'text-slate-500 hover:bg-slate-50' => app()->getLocale() !== 'en',
            ])>EN</a>
        </div>


        <!-- Bascule thème clair / sombre -->
        <button type="button"
            onclick="var d=document.documentElement.classList.toggle('dark');try{localStorage.setItem('gp-theme',d?'dark':'light')}catch(e){}"
            title="{{ __('Toggle theme') }}" aria-label="{{ __('Toggle theme') }}"
            class="border rounded-md p-1 shadow-sm flex items-center justify-center bg-white text-slate-800 hover:bg-slate-50 transition">
            <!-- Lune (mode clair actif → propose le sombre) -->
            <svg class="theme-icon-moon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
            </svg>
            <!-- Soleil (mode sombre actif → propose le clair) -->
            <svg class="theme-icon-sun w-6 h-6 text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="4" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32 1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32 1.41-1.41" />
            </svg>
        </button>

        <!-- Réglages (Admin) -->
        @if (Auth::user()->isAdmin())
            <a href="{{ route('settings.index') }}" wire:navigate title="{{ __('Settings') }}"
                @class([
                    'border rounded-md p-1 shadow-sm flex items-center justify-center transition',
                    'bg-[#134169] text-white border-[#134169]' => request()->routeIs('settings.*'),
                    'bg-white text-slate-800 hover:bg-slate-50' => ! request()->routeIs('settings.*'),
                ])>
                <svg class="w-6 h-6 {{ request()->routeIs('settings.*') ? 'text-white' : 'text-gray-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33h.01a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51h.01a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.01z" />
                </svg>
            </a>
        @endif
    </div>
</header>