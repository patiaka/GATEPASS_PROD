<aside id="sidebar"
    class="sidebar fixed md:static inset-y-0 left-0 z-40 flex flex-col w-64 p-4 bg-[#0e3a61] text-white border-r border-white/10 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">

    <a href="/" wire:navigate class="mb-3 px-2 pb-2 flex rounded-md">
        <img src="{{ asset('assets/img/logo2.png') }}" alt="Logo" class="w-32">
    </a>

    <a href="/"
       class="logo-wrapper p-2 flex items-center gap-2 bg-white/90 text-slate-700 border rounded-md border-white/70 shadow hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
        <div class="flex items-center justify-center h-9 w-9 bg-[#ffd324] rounded text-white border border-[#edcb34] shadow-lg shrink-0">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-5" aria-hidden="true">
                <path fill="currentColor" d="M9.715 12c1.151 0 2-.849 2-2s-.849-2-2-2s-2 .849-2 2s.848 2 2 2"></path>
                <path fill="currentColor" d="M20 4H4c-1.103 0-2 .841-2 1.875v12.25C2 19.159 2.897 20 4 20h16c1.103 0 2-.841 2-1.875V5.875C22 4.841 21.103 4 20 4m0 14l-16-.011V6l16 .011z"></path>
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="font-medium text-xs leading-tight">Gate Pass Management</span>
            <span class="text-[10px] text-slate-500 leading-tight">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </a>

    <div class="border-b my-3"></div>

    <div class="flex flex-col h-full overflow-y-auto pr-1">
        <h2 class="ml-3 mb-2 uppercase tracking-wider text-[11px] font-semibold text-white/70">Menu</h2>

        <ul class="flex flex-col gap-1.5">
            <li>
                <a wire:navigate href="/"
                   class="group flex items-center gap-3 py-2 px-3 rounded-md transition-colors
                          {{ request()->is('/') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'bg-white/10 text-white hover:bg-white/15' }}">
                    <svg class="w-4 h-4 shrink-0 {{ request()->is('/') ? 'text-white' : 'text-white/80 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2H9a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2z" />
                    </svg>
                    <span class="text-xs">Dashboard</span>
                </a>
            </li>

            @if (Auth::user()->isAdmin())
            <li>
                @php $dbOpen = request()->routeIs('user.*') || request()->routeIs('department.*'); @endphp
                <details class="group" {{ $dbOpen ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 px-3 py-2 rounded transition-colors hover:bg-white/10 hover:text-white cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                        <svg class="w-4 h-4 text-white/90 group-hover:text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <ellipse cx="12" cy="5" rx="9" ry="3" />
                            <path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5" />
                        </svg>
                        <span class="text-xs font-normal">Databases</span>
                        <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="mt-1 ml-3 border-l border-white/10 pl-2 space-y-1">
                        <li>
                            <a wire:navigate href="{{ route('user.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('user.*') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('user.*') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                                User Database
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('department.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('department.*') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('department.*') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-8a4 4 0 014-4h10a4 4 0 014 4v8M7 21v-4h10v4M7 10V7a4 4 0 018 0v3" />
                                </svg>
                                Department Database
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            @endif

            <li>
                @php
                    $gpOpen = request()->routeIs('car.create') || request()->routeIs('material.create') || request()->routeIs('car.index') || request()->routeIs('material.index') || request()->routeIs('car.check') || request()->routeIs('material.check');
                @endphp
                <details class="group" {{ $gpOpen ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 px-3 py-2 rounded transition-colors hover:bg-white/10 hover:text-white cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                        <svg class="w-4 h-4 text-white/90 group-hover:text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V4a2 2 0 0 1 2-2h5l2 2h5a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2z" />
                        </svg>
                        <span class="text-xs font-normal">Gate Pass Requests</span>
                        <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="mt-1 ml-3 border-l border-white/10 pl-2 space-y-1">
                        <li>
                            <a wire:navigate href="{{ route('car.create') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('car.create') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('car.create') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h1l2 3h12l2-3h1" />
                                    <circle cx="7" cy="18" r="2" />
                                    <circle cx="17" cy="18" r="2" />
                                    <path d="M5 10V6a2 2 0 012-2h10a2 2 0 012 2v4" />
                                </svg>
                                Vehicle Offsite Form
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('material.create') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('material.create') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('material.create') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12v8" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12L3 7" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7l-9 5" />
                                </svg>
                                Material Form
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('car.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('car.index') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('car.index') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18" />
                                </svg>
                                All Gatepass Requests
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('material.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('material.index') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('material.index') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18" />
                                </svg>
                                All Material Requests
                            </a>
                        </li>

                        @if (Auth::user()->isAdmin() || Auth::user()->isSecurity())
                        <li>
                            <a wire:navigate href="{{ route('car.check') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('car.check') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('car.check') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Offsite Check In / Out
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('material.check') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('material.check') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('material.check') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Material Check In / Out
                            </a>
                        </li>
                        @endif
                    </ul>
                </details>
            </li>

            @if (Auth::user()->isGm() || Auth::user()->isHod())
            <li>
                @php $apprOpen = request()->routeIs('material.pending') || request()->routeIs('car.pending'); @endphp
                <details class="group" {{ $apprOpen ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 px-3 py-2 rounded transition-colors hover:bg-white/10 hover:text-white cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                        <svg class="w-4 h-4 text-white/90 group-hover:text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs font-normal">Approvals</span>
                        <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="mt-1 ml-3 border-l border-white/10 pl-2 space-y-1">
                        <li>
                            <a wire:navigate href="{{ route('material.pending') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('material.pending') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('material.pending') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                Material Pending Requests
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('car.pending') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('car.pending') ? 'bg-[#0e3a61]/30 text-white font-medium shadow-sm' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('car.pending') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                Gate Pass Pending Requests
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            @endif

            <li>
                @php $repOpen = request()->routeIs('reports.*'); @endphp
                <details class="group" {{ $repOpen ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 px-3 py-2 rounded transition-colors hover:bg-white/10 hover:text-white cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                        <svg class="w-4 h-4 text-white/90 group-hover:text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                        </svg>
                        <span class="text-xs font-normal">Reports</span>
                        <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="mt-1 ml-3 border-l border-white/10 pl-2 space-y-1">
                        <li>
                            <a href="#"
                               class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs hover:bg-white/10 hover:text-white">
                                <svg class="w-4 h-4 shrink-0 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.5 2-3 3-3 5a3 3 0 006 0c0-2-1.5-3-3-5z" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                Offsite Records
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
        </ul>

        <div class="mt-auto border border-white/15 rounded-xl px-4 py-3 bg-white/5">
            <details class="group">
                <summary class="flex items-center gap-3 list-none cursor-pointer rounded hover:bg-white/10 px-2 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                    <div class="flex items-center justify-center w-9 h-9 bg-white/80 rounded-full border border-white/60 text-[#0E3A61] font-semibold uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col max-w-[160px] truncate">
                        <span class="font-medium text-xs truncate">{{ Auth::user()->name }}</span>
                        <span class="text-[11px] text-white/70 truncate">{{ Auth::user()->email }}</span>
                    </div>
                    <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <ul class="border-t border-white/10 mt-2 pt-2 space-y-1">
                    <li>
                        <a wire:navigate href="{{ route('user.pass') }}"
                           class="block px-3 py-2 rounded hover:bg-white/10 text-xs flex items-center gap-3 transition-colors">
                            <svg class="w-4 h-4 text-white/85 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="20" height="14" x="2" y="7" rx="2" ry="2" />
                                <path d="M7 7V5a5 5 0 0110 0v2" />
                            </svg>
                            Change Password
                        </a>
                    </li>
                    <li>
                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="block px-3 py-2 rounded hover:bg-white/10 text-xs flex items-center gap-3 transition-colors"
                           href="{{ route('logout') }}">
                            <svg class="w-4 h-4 text-white/85 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 01-2-2h6a2 2 0 012 2v1" />
                            </svg>
                            Logout
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                            </form>
                        </a>
                    </li>
                </ul>
            </details>
        </div>
    </div>
</aside>
