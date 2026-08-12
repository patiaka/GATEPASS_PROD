<div id="overlay" onclick="document.documentElement.classList.remove('nav-open')" class="fixed inset-0 bg-black/50 z-30"></div>
<aside id="sidebar"
    class="sidebar inset-y-0 left-0 z-40 flex flex-col min-h-0 h-screen h-[100dvh] w-72 max-w-[85vw] p-4 bg-[#0e3a61] text-white border-r border-white/10">

    <a href="/" class="mb-3 px-2 pb-2 flex rounded-md">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-32">
    </a>

    <a href="/"
        class="logo-wrapper p-2 flex items-center gap-2 bg-white/90 text-slate-700 border rounded-md border-white/70 shadow hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
        <div
            class="flex items-center justify-center h-9 w-9 bg-[#ffd324] rounded text-white border border-[#edcb34] shadow-lg shrink-0">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-5" aria-hidden="true">
                <path fill="currentColor" d="M9.715 12c1.151 0 2-.849 2-2s-.849-2-2-2s-2 .849-2 2s.848 2 2 2"></path>
                <path fill="currentColor"
                    d="M20 4H4c-1.103 0-2 .841-2 1.875v12.25C2 19.159 2.897 20 4 20h16c1.103 0 2-.841 2-1.875V5.875C22 4.841 21.103 4 20 4m0 14l-16-.011V6l16 .011z">
                </path>
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="font-bold bold text-xs leading-tight">{{ __('Gate Pass Management') }}</span>
            <span class="text-[10px] text-slate-500 leading-tight">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </a>

    <div class="border-b my-3"></div>

    <div class="flex flex-col flex-1 min-h-0 overflow-y-auto pr-1">
        <div class="flex items-center justify-between mb-2 pr-1">
            <h2 class="ml-3 uppercase tracking-wider text-sm font-medium text-white/70">{{ __('Menu') }}</h2>
            <button id="sidebarCollapse" type="button" title="{{ __('Collapse sidebar') }}" aria-label="{{ __('Collapse sidebar') }}"
                onclick="var m=document.documentElement.classList.toggle('nav-mini');try{localStorage.setItem('gp-sidebar-mini',m?'1':'0')}catch(e){}"
                class="hidden xl:inline-flex items-center justify-center w-7 h-7 rounded-md text-white/70 hover:bg-white/10 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7M18 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <ul class="flex flex-col gap-1.5">
            <li>
                <a href="/" wire:navigate
                    class="group flex items-center gap-3 py-2 px-3 rounded-md transition-colors
              {{ request()->is('/') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 shrink-0 {{ request()->is('/') ? 'text-white' : 'text-white/80 group-hover:text-white' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" />
                        <rect x="14" y="3" width="7" height="7" rx="1.5" />
                        <rect x="14" y="14" width="7" height="7" rx="1.5" />
                        <rect x="3" y="14" width="7" height="7" rx="1.5" />
                    </svg>
                    <span class="text-sm font-normal">{{ __('Dashboard') }}</span>
                </a>
            </li>

            @if (Auth::user()->isAdmin())
                <li>
                    @php $dbOpen = request()->routeIs('user.*') || request()->routeIs('department.*') || request()->routeIs('audit.*'); @endphp
                    <details class="group" {{ $dbOpen ? 'open' : '' }}>
                        <summary
                            class="flex items-center gap-3 px-3 py-2 rounded transition-colors group-open:bg-white/5 group-open:text-white hover:bg-white/10 hover:text-white cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                            <svg class="w-6 h-6 text-white/90 group-hover:text-white shrink-0" fill="none"
                                stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <ellipse cx="12" cy="5" rx="8" ry="3" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5v6c0 1.657 3.582 3 8 3s8-1.343 8-3V5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 11v6c0 1.657 3.582 3 8 3s8-1.343 8-3v-6" />
                            </svg>
                            <span class="text-sm font-normal">{{ __('Databases') }}</span>
                            <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <ul class="mt-1 ml-3 border-l border-white/10 pl-2 space-y-1">
                            <li>
                                <a wire:navigate href="{{ route('user.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('user.*') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('user.*') ? 'text-white' : 'text-white/80' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                    </svg>
                                    <span class="text-sm">{{ __('User Database') }}</span>
                                </a>
                            </li>
                            <li>
                                <a wire:navigate href="{{ route('department.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('department.*') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('department.*') ? 'text-white' : 'text-white/80' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 21v-8a4 4 0 014-4h10a4 4 0 014 4v8M7 21v-4h10v4M7 10V7a4 4 0 018 0v3" />
                                    </svg>
                                    <span class="text-sm">{{ __('Department Database') }}</span>
                                </a>
                            </li>
                            <li>
                                <a wire:navigate href="{{ route('audit.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('audit.*') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('audit.*') ? 'text-white' : 'text-white/80' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                                    </svg>
                                    <span class="text-sm">{{ __('Audit log') }}</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
            @endif

            <li>
                @php
                    $gpOpen =
                        request()->routeIs('car.create') ||
                        request()->routeIs('material.create') ||
                        request()->routeIs('car.index') ||
                        request()->routeIs('material.index') ||
                        request()->routeIs('car.check') ||
                        request()->routeIs('material.check');
                @endphp
                <details class="group" {{ $gpOpen ? 'open' : '' }}>
                    <summary
                        class="flex items-center gap-3 px-3 py-2 rounded transition-colors group-open:bg-white/5 group-open:text-white hover:bg-white/10 hover:text-white cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                        <svg class="w-6 h-6 text-white/90 group-hover:text-white shrink-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                        </svg>
                        <span class="text-sm font-normal">{{ __('Gate Pass Requests') }}</span>
                        <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="mt-1 ml-3 border-l border-white/10 pl-2 space-y-1">
                        <li>
                            <a href="{{ route('car.create') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
             {{ request()->routeIs('car.create') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('car.create') ? 'text-white' : 'text-white/80' }}"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13l2-5a2 2 0 0 1 1.9-1.4h10.2A2 2 0 0 1 19 8l2 5m-18 0v4a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-4m-18 0h18M6.5 16.5h.01M17.5 16.5h.01" />
                                </svg>
                                {{-- <span class="text-sm">New Vehicle Offsite Form</span> --}}
                                <span class="text-sm">{{ __('New Vehicle Form') }}</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('material.create') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
             {{ request()->routeIs('material.create') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('material.create') ? 'text-white' : 'text-white/80' }}"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                <span class="text-sm">{{ __('New Material Form') }}</span>
                            </a>
                        </li>

                        <li>
                            <a wire:navigate href="{{ route('car.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('car.index') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('car.index') ? 'text-white' : 'text-white/80' }}"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18" />
                                </svg>
                                <span class="text-sm">{{ __('All Gatepass Requests') }}</span>
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{ route('material.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('material.index') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('material.index') ? 'text-white' : 'text-white/80' }}"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18" />
                                </svg>
                                <span class="text-sm">{{ __('All Material Requests') }}</span>
                            </a>
                        </li>

                        @if (Auth::user()->isAdmin() || Auth::user()->isSecurity() || Auth::user()->isGm())
                            <li>
                                <a wire:navigate href="{{ route('car.check') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('car.check') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('car.check') ? 'text-white' : 'text-white/80' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                    </svg>
                                    {{-- <span class="text-sm">Vehicle Offsite Check In / Out</span> --}}
                                    <span class="text-sm">{{ __('Vehicle Check In / Out') }}</span>
                                </a>
                            </li>
                            <li>
                                <a wire:navigate href="{{ route('material.check') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('material.check') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('material.check') ? 'text-white' : 'text-white/80' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                    </svg>
                                    <span class="text-sm">{{ __('Material Check In / Out') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </details>
            </li>

            @if (Auth::user()->isAdmin() || Auth::user()->isSecurity())
                <li>
                    <a wire:navigate href="{{ route('gate.console') }}"
                        class="group flex items-center gap-3 py-2 px-3 rounded-md transition-colors
                          {{ request()->routeIs('gate.console') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('gate.console') ? 'text-white' : 'text-white/80 group-hover:text-white' }}"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                        <span class="text-sm font-normal">{{ __('Vehicle gate console') }}</span>
                    </a>
                </li>
                <li>
                    <a wire:navigate href="{{ route('material.gate.console') }}"
                        class="group flex items-center gap-3 py-2 px-3 rounded-md transition-colors
                          {{ request()->routeIs('material.gate.console') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('material.gate.console') ? 'text-white' : 'text-white/80 group-hover:text-white' }}"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-sm font-normal">{{ __('Material gate console') }}</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->isApprover())
                <li>
                    @php $apprOpen = request()->routeIs('material.pending') || request()->routeIs('car.pending'); @endphp
                    <details class="group" {{ $apprOpen ? 'open' : '' }}>
                        <summary
                            class="flex items-center gap-3 px-3 py-2 rounded transition-colors group-open:bg-white/5 group-open:text-white hover:bg-white/10 hover:text-white cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                            <svg class="w-6 h-6 text-white/90 group-hover:text-white shrink-0" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.573-.598-3.751h-.152c-3.196 0-6.1-1.25-8.25-3.285z" />
                            </svg>
                            <span class="text-sm font-normal">{{ __('Approvals') }}</span>
                            @php $apprCount = Auth::user()->awaitingApprovalCount(); @endphp
                            @if ($apprCount > 0)
                                <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-[#0e3a61] bg-[#ffd324] rounded-full">{{ $apprCount }}</span>
                            @endif
                            <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <ul class="mt-1 ml-3 border-l border-white/10 pl-2 space-y-1">
                            <li>
                                <a wire:navigate href="{{ route('car.pending') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('car.pending') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('car.pending') ? 'text-white' : 'text-white/80' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                        <circle cx="12" cy="12" r="10" />
                                    </svg>
                                    <span class="text-sm">{{ __('Gate Pass Pending Requests') }}</span>
                                </a>
                            </li>
                            <li>
                                <a wire:navigate href="{{ route('material.pending') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('material.pending') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('material.pending') ? 'text-white' : 'text-white/80' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                        <circle cx="12" cy="12" r="10" />
                                    </svg>
                                    <span class="text-sm">{{ __('Material Pending Requests') }}</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
            @endif

            @if (Auth::user()->isAdmin() || Auth::user()->isGm() || Auth::user()->isSecurity())
            <li>
                @php $repOpen = request()->routeIs('reports.*'); @endphp
                <details class="group" {{ $repOpen ? 'open' : '' }}>
                    <summary
                        class="flex items-center gap-3 px-3 py-2 rounded transition-colors group-open:bg-white/5 group-open:text-white hover:bg-white/10 hover:text-white cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                        {{-- <svg class="w-4 h-4 text-white/90 group-hover:text-white shrink-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                        </svg> --}}
                        
                        <svg class="w-6 h-6 text-white/90 group-hover:text-white shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill="currentColor" d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8zm4 18H6V4h7v5h5zm-9-7v6H7v-6zm6 2v4h2v-4zm-4-4v8h2v-8z"></path>
                        </svg>

                        <span class="text-sm font-normal">{{ __('Reports') }}</span>
                        <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="mt-1 ml-3 border-l border-white/10 pl-2 space-y-1">
                        <li>
                            <a wire:navigate href="{{ route('reports.offsite') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded transition-colors text-xs
                                      {{ request()->routeIs('reports.offsite') ? 'bg-white/15 text-white font-semibold shadow-[inset_3px_0_0_0_#ffd324]' : 'hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('reports.offsite') ? 'text-white' : 'text-white/80' }}" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-6M12 17V7M15 17v-4M3 3v18h18" />
                                </svg>
                                <span class="text-sm">{{ __('Offsite Records') }}</span>
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            @endif
        </ul>
    </div>

    {{-- Profil + Déconnexion : épinglé en pied de sidebar (hors zone défilante) --}}
    <div class="sidebar-footer shrink-0 mt-3 border border-white/15 rounded-xl px-4 py-3 bg-white/5">
            <details class="group">
                <summary
                    class="flex items-center gap-3 list-none cursor-pointer rounded hover:bg-white/10 px-2 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                    <div
                        class="flex items-center justify-center w-9 h-9 bg-white/80 rounded-full border border-white/60 text-[#0E3A61] font-semibold uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col max-w-[160px] truncate">
                        <span class="font-medium text-xs truncate">{{ Auth::user()->name }}</span>
                        <span class="text-[11px] text-white/70 truncate">{{ Auth::user()->email }}</span>
                    </div>
                    <svg class="ml-auto w-4 h-4 text-white/80 transition-transform duration-200 group-open:rotate-180"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <ul class="border-t border-white/10 mt-2 pt-2 space-y-1">
                    <li>
                        <a wire:navigate href="{{ route('user.pass') }}"
                            class="px-3 py-2 rounded hover:bg-white/10 text-xs flex items-center gap-3 transition-colors">
                            <svg class="w-4 h-4 text-white/85 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <rect width="20" height="14" x="2" y="7" rx="2" ry="2" />
                                <path d="M7 7V5a5 5 0 0110 0v2" />
                            </svg>
                            {{ __('Change Password') }}
                        </a>
                    </li>
                    <li>
                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="px-3 py-2 rounded hover:bg-white/10 text-xs flex items-center gap-3 transition-colors"
                            href="{{ route('logout') }}">
                            <svg class="w-4 h-4 text-white/85 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 01-2-2h6a2 2 0 012 2v1" />
                            </svg>
                            {{ __('Logout') }}
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                            </form>
                        </a>
                    </li>
                </ul>
            </details>
    </div>
</aside>
