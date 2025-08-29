<aside id="sidebar"
    class="sidebar fixed md:static inset-y-0 left-0 z-40 flex flex-col w-64 p-4 bg-[#0e3a61] text-white border-r transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">

    <a href="/" wire:navigate class="mb-2 px-2 pb-2 flex rounded-md">
        <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="w-32">
    </a>

    <a href="/" class="logo-wrapper p-2 flex items-center gap-2 bg-slate-100 text-slate-600 border-2 rounded-md">
        <div
            class="flex items-center justify-center h-10 w-10 bg-[#ffd324] rounded text-white border border-[#edcb34] shadow-lg">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-6" aria-hidden="true">
                <path fill="currentColor" d="M9.715 12c1.151 0 2-.849 2-2s-.849-2-2-2s-2 .849-2 2s.848 2 2 2"></path>
                <path fill="currentColor"
                    d="M20 4H4c-1.103 0-2 .841-2 1.875v12.25C2 19.159 2.897 20 4 20h16c1.103 0 2-.841 2-1.875V5.875C22 4.841 21.103 4 20 4m0 14l-16-.011V6l16 .011z">
                </path>
                <path fill="currentColor"
                    d="M14 9h4v2h-4zm1 4h3v2h-3zm-1.57 2.536c0-1.374-1.676-2.786-3.715-2.786S6 14.162 6 15.536V16h7.43z">
                </path>
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="font-semibold text-sm">Gate Pass Management</span>
            <span class="text-xs">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </a>

    <div class="w-full border-b bg-gray-200 my-4"></div>

    <div class="flex flex-col h-full overflow-y-auto">
        <h2 class="font-medium ml-4 mb-4">MENU</h2>

        <ul class="flex flex-col gap-3">

            <li>
                <a wire:navigate href="/"
                    class="active flex items-center gap-3 py-2 px-4 menu-item hover:bg-[#0e3a6192] hover:text-slate-100 rounded-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2H9a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2z" />
                    </svg>
                    <span class="font-normal text-sm">Dashboard</span>
                </a>
            </li>

            <!-- Databases -->
            @if (Auth::user()->isAdmin())
            <li>
                <details>
                    <summary
                        class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <ellipse cx="12" cy="5" rx="9" ry="3" />
                            <path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5" />
                        </svg>
                        <span class="text-sm font-normal">Databases</span>
                        <svg class="ml-auto w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="submenu mt-1">
                        <li class="mb-1">
                            <a wire:navigate href="{{ route('user.index') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                                User Database
                            </a>
                        </li>
                        <li class="mb-1">
                            <a wire:navigate href="{{ route('department.index') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 21v-8a4 4 0 014-4h10a4 4 0 014 4v8M7 21v-4h10v4M7 10V7a4 4 0 018 0v3" />
                                </svg>
                                Department Database
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            @endif
            <!-- Gate Pass Requests -->
            <li>
                <details>
                    <summary
                        class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V4a2 2 0 012-2h5l2 2h5a2 2 0 012 2v12a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-normal">Gate Pass Requests</span>
                        <svg class="ml-auto w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="submenu mt-1">
                        <li class="mb-1">
                            <a wire:navigate href="{{ route('car.create') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h1l2 3h12l2-3h1" />
                                    <circle cx="7" cy="18" r="2" />
                                    <circle cx="17" cy="18" r="2" />
                                    <path d="M5 10V6a2 2 0 012-2h10a2 2 0 012 2v4" />
                                </svg>
                                Vehicle Offsite Form
                            </a>
                        </li>
                        <li class="mb-1">
                            <a wire:navigate href="{{ route('material.create') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12v8" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12L3 7" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7l-9 5" />
                                </svg>
                                Material Form
                            </a>
                        </li>
                        <li class="mb-1">
                            <a wire:navigate href="{{ route('car.index') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18" />
                                </svg>
                                All Gatepass Requests
                            </a>
                        </li>
                        <li class="mb-1">
                            <a wire:navigate href="{{ route('material.index') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18" />
                                </svg>
                                All Material Requests
                            </a>
                        </li>


                        @if (Auth::user()->isAdmin() || Auth::user()->isSecurity())
                        <li class="mb-1">
                            <a wire:navigate href="{{ route('car.check') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Car Check In / Out
                            </a>
                        </li>

                        <li class="mb-1">
                            <a wire:navigate href="{{ route('material.check') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Material Check In / Out
                            </a>
                        </li>
                        @endif
                    </ul>
                </details>
            </li>

            @if (Auth::user()->isGm() || Auth::user()->isHod())
            <!-- Approvals -->
            <li>
                <details>
                    <summary
                        class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm font-normal">Approvals</span>
                        <svg class="ml-auto w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="submenu mt-1">
                        <li class="mb-1">
                            <a wire:navigate href="{{ route('material.pending') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                Material Pending Requests
                            </a>

                            <a wire:navigate href="{{ route('car.pending') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
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
            <!-- Reports -->
            <li>
                <details>
                    <summary
                        class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                        </svg>
                        <span class="text-sm font-normal">Reports</span>
                        <svg class="ml-auto w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <ul class="submenu mt-1">
                        <li class="mb-1">
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2 rounded hover:bg-[#0e3a615d] hover:text-white text-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8c-1.5 2-3 3-3 5a3 3 0 006 0c0-2-1.5-3-3-5z" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                Offsite Records
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
        </ul>

        <!-- Footer -->
        <div class="mt-auto border border-gray-600 rounded-md px-4 py-3 cursor-pointer bg-[#0E3A61]">
            <details>
                <summary class="flex items-center gap-3 list-none cursor-pointer">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full border text-[#0E3A61] font-semibold">
                        O</div>
                    <div class="flex flex-col max-w-[160px] truncate">
                        <span class="font-medium text-sm truncate">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</span>
                    </div>
                    <svg class="ml-auto w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <ul class="submenu border-t border-gray-600 mt-2 pt-2">
                    <li class="mb-1">
                        <a wire:navigate href="{{ route('user.pass') }}"
                            class="block px-4 py-2 hover:bg-[#ffffff53] text-sm flex items-center gap-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <rect width="20" height="14" x="2" y="7" rx="2" ry="2" />
                                <path d="M7 7V5a5 5 0 0110 0v2" />
                            </svg>
                            Change Password
                        </a>
                    </li>
                    <li class="mb-1">
                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block px-4 py-2 hover:bg-[#ffffff53] text-sm flex items-center gap-3"
                            href="{{ route('logout') }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                            </svg>
                            Logout
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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