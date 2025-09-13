<div>
    <!-- Bandeau branding fin + compact -->
    <div class="grid grid-cols-5 mb-6 p-0.5 bg-white gap-0.5 rounded-sm overflow-hidden shadow-sm">
        <div class="bg-yellow-300 h-3 w-full col-span-1"></div>
        <div class="bg-[#134169] h-3 w-full col-span-4"></div>
    </div>

    <!-- Titre + sous-texte compact -->
    <div class="flex items-end justify-between">
        <h1 class="font-semibold text-lg text-slate-900">Dashboard</h1>
        <span class="text-[11px] text-slate-500 hidden md:inline">Overview & quick stats</span>
    </div>

    <div class="rounded-2xl p-4 col-span-12 grid grid-cols-12 gap-4 md:gap-5 border shadow-xs mt-4 bg-white">
        <h2 class="text-sm font-semibold tracking-wide text-slate-700 col-span-12 flex items-center gap-2">
            <span class="inline-block h-2 w-2 rounded-full bg-[#0e3a61]"></span>
            Overview
        </h2>

        <!-- Cards grid -->
        <div class="col-span-12 grid grid-cols-1 gap-4 md:gap-5 xl:grid-cols-4 2xl:grid-cols-5">
            @if (Auth::user()->isAdmin() | Auth::user()->isSecurity())
            <!-- Visitor -->
            <a href="{{ route('car.check') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">Gatepass</h3>
                        <p class="text-[12px] text-slate-500">Checked out Gatepass</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $car_check_out }}</span>
            </a>

            <!-- Material check -->
            <a href="{{ route('material.check') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">Material</h3>
                        <p class="text-[12px] text-slate-500">Checked out Material</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $mat_check_out }}</span>
            </a>
            @endif

            <!-- All gatepass -->
            <a href="{{ route('car.index') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">All</h3>
                        <p class="text-[12px] text-slate-500">Gate pass Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $car_request_all }}</span>
            </a>

            <!-- Approved gatepass -->
            <a href="{{ route('car.index') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-green-300 rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17l-3.88-3.88L4 13.41l5 5 12-12-1.41-1.41z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">Gate pass Approved</h3>
                        <p class="text-[12px] text-slate-500">Visitor Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $car_request_approved }}</span>
            </a>

            <!-- Pending gatepass -->
            <a href="{{ route('car.pending') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-yellow-300 rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4a8 8 0 100 16 8 8 0 000-16zm.5 9H12a.5.5 0 01-.5-.5V8H11v5a1 1 0 001 1h1v-1z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">Gate pass Pending</h3>
                        <p class="text-[12px] text-slate-500">Visitor Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $car_request_pending }}</span>
            </a>

            <!-- Rejected gatepass -->
            <a href="{{ route('car.index') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-red-300 rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12c0 5.52 4.48 10 10 10s10-4.48 10-10c0-5.52-4.48-10-10-10zm5 13.59L8.41 7 7 8.41 15.59 17 17 15.59z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">Gate pass Rejected</h3>
                        <p class="text-[12px] text-slate-500">Visitor Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $car_request_rejected }}</span>
            </a>

            <!-- All material -->
            <a href="{{ route('material.index') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">All</h3>
                        <p class="text-[12px] text-slate-500">Material Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $mat_request_all }}</span>
            </a>

            <!-- Approved material -->
            <a href="{{ route('material.index') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-green-300 rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17l-3.88-3.88L4 13.41l5 5 12-12-1.41-1.41z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">Material Approved</h3>
                        <p class="text-[12px] text-slate-500">Visitor Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $mat_request_approved }}</span>
            </a>

            <!-- Pending material -->
            <a href="{{ route('material.pending') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-yellow-300 rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4a8 8 0 100 16 8 8 0 000-16zm.5 9H12a.5.5 0 01-.5-.5V8H11v5a1 1 0 001 1h1v-1z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">Material Pending</h3>
                        <p class="text-[12px] text-slate-500">Visitor Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $mat_request_pending }}</span>
            </a>

            <!-- Rejected material -->
            <a href="{{ route('material.index') }}"
               class="group p-4 flex items-center justify-between bg-white border border-gray-200 rounded-2xl shadow-xs hover:shadow-md transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e3a61]/40">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-red-300 rounded-full h-12 w-12 flex items-center justify-center ring-1 ring-[#134169]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12c0 5.52 4.48 10 10 10s10-4.48 10-10c0-5.52-4.48-10-10-10zm5 13.59L8.41 7 7 8.41 15.59 17 17 15.59z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 uppercase">Material Rejected</h3>
                        <p class="text-[12px] text-slate-500">Visitor Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-900">{{ $mat_request_rejected }}</span>
            </a>
        </div>
    </div>
</div>
