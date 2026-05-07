<div>
    <!-- Progress bar -->
    <div class="grid grid-cols-5 mb-6 p-0.5 bg-white gap-0.5 rounded-sm overflow-hidden shadow-sm">
        <div class="bg-yellow-300 h-3 w-full col-span-1"></div>
        <div class="bg-[#134169] h-3 w-full col-span-4"></div>
    </div>

    <!-- Dashboard Title -->
    <div>
        {{-- <h1 class="font-medium text-2xl text-slate-800">Dashboard</h1> --}}
        <h1 class="font-medium text-xl">Dashboard</h1>
    </div>

    <!-- Overview section -->
    <div class="rounded-2xl mt-4 p-4 col-span-12 grid grid-cols-12 gap-4 md:gap-5 border border-gray-200">
        {{-- <h2 class="text-xl font-semibold text-slate-800 col-span-12">Overview</h2> --}}
        <h2>Overview</h2>

        <div class="col-span-12 grid grid-cols-1 gap-4 md:gap-5 xl:grid-cols-4 2xl:grid-cols-5">
            @if (Auth::user()->isAdmin() || Auth::user()->isSecurity())
            <!-- ========================= Gatepass Cards ========================= -->
            <!-- All -->
            <a wire:navigate href="{{ route('car.index') }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">All</h3>
                        <p class="text-xs text-slate-500">Gate pass Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $car_request_all }}</span>
            </a>

            <!-- Gatepass Checked Out -->
            <a wire:navigate href="{{ route('car.check') }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Gatepass</h3>
                        <p class="text-xs text-slate-500">Checked out Gatepass</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $car_check_out }}</span>
            </a>

            <!-- Approved -->
            <a wire:navigate href="{{ route('car.index', ['by_status' => 'Approved']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-green-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17l-3.88-3.88L4 13.41l5 5 12-12-1.41-1.41z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Gatepass Approved</h3>
                        <p class="text-xs text-slate-500">Approved Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $car_request_approved }}</span>
            </a>

            <!-- Pending -->
            <a  wire:navigate href="{{ route('car.index', ['by_status' => 'Pending']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-yellow-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 4a8 8 0 100 16 8 8 0 000-16zm.5 9H12a.5.5 0 01-.5-.5V8H11v5a1 1 0 001 1h1v-1z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Gatepass Pending</h3>
                        <p class="text-xs text-slate-500">Pending Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $car_request_pending }}</span>
            </a>

            <!-- Rejected -->
            <a  wire:navigate href="{{ route('car.index', ['by_status' => 'Rejected']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-red-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12c0 5.52 4.48 10 10 10s10-4.48 10-10c0-5.52-4.48-10-10-10zm5 13.59L8.41 7 7 8.41 15.59 17 17 15.59z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Gatepass Rejected</h3>
                        <p class="text-xs text-slate-500">Rejected Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $car_request_rejected }}</span>
            </a>

            <!-- ========================= Material Cards ========================= -->

            <!-- All -->
            <a  wire:navigate href="{{ route('material.index') }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">All</h3>
                        <p class="text-xs text-slate-500">Material Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $mat_request_all }}</span>
            </a>

            <!-- Material Checked Out -->
            <a wire:navigate href="{{ route('material.check') }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-white rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Material</h3>
                        <p class="text-xs text-slate-500">Checked out Material</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $mat_check_out }}</span>
            </a>
            @endif

            <!-- Approved -->
            <a  wire:navigate href="{{ route('material.index', ['by_status' => 'Approved']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-green-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17l-3.88-3.88L4 13.41l5 5 12-12-1.41-1.41z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Material Approved</h3>
                        <p class="text-xs text-slate-500">Approved Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $mat_request_approved }}</span>
            </a>

            <!-- Pending -->
            <a  wire:navigate href="{{ route('material.index', ['by_status' => 'Pending']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-yellow-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 4a8 8 0 100 16 8 8 0 000-16zm.5 9H12a.5.5 0 01-.5-.5V8H11v5a1 1 0 001 1h1v-1z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Material Pending</h3>
                        <p class="text-xs text-slate-500">Pending Material Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $mat_request_pending }}</span>
            </a>

            <!-- Rejected -->
            <a  wire:navigate href="{{ route('material.index', ['by_status' => 'Rejected']) }}"
                class="p-3 flex items-center justify-between bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                <div class="flex gap-3 items-center">
                    <span class="bg-[#134169] text-red-300 rounded-full h-12 w-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12c0 5.52 4.48 10 10 10s10-4.48 10-10c0-5.52-4.48-10-10-10zm5 13.59L8.41 7 7 8.41 15.59 17 17 15.59z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 uppercase">Material Rejected</h3>
                        <p class="text-xs text-slate-500">Rejected Requests</p>
                    </div>
                </div>
                <span class="text-xl font-semibold text-slate-800">{{ $mat_request_rejected }}</span>
            </a>
        </div>


    </div>
    <!-- ========================= Latest Check-in / Check-out Tables ========================= -->
    @if (Auth::user()->isGm() || Auth::user()->isHod() || Auth::user()->isAdmin())

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-8">

        <!-- ================= Gatepass Table ================= -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden">
            <div class="flex justify-between items-center border-b bg-slate-50 px-5 py-3">
                <h1 class="font-semibold text-base text-[#134169]">
                    Latest Gatepass Check-in / Check-out
                </h1>
                <a href="{{ route('car.check') }}" class="text-xs text-[#134169] border border-[#134169] px-3 py-1 rounded-lg
                      hover:bg-[#134169] hover:text-white transition">
                    Show All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-[12px]">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr class="uppercase tracking-wide">
                            <th class="px-3 py-2 text-left font-semibold">ID</th>
                            <th class="px-3 py-2 text-left font-semibold">Date</th>
                            <th class="px-3 py-2 text-left font-semibold">Agent</th>
                            <th class="px-3 py-2 text-left font-semibold">Vehicle No</th>
                            <th class="px-3 py-2 text-left font-semibold">Compagny</th>
                            <th class="px-3 py-2 text-left font-semibold">Department /Driver</th>
                            <th class="px-4 py-3 text-left font-medium">gate</th>
                            {{-- <th class="px-4 py-3 text-left font-medium">fuel level</th> --}}
                            {{-- <th class="px-4 py-3 text-left font-medium">destination</th> --}}
                            {{-- <th class="px-4 py-3 text-left font-medium">kilometers / Per Hours</th> --}}
                            <th class="px-3 py-2 text-left font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($car_check_latest as $row)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-3 py-2 font-medium text-gray-800">
                                #{{ $row->requestable->reference }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ $row->created_at }}
                            </td>

                            <td class="px-3 py-2 text-gray-700">
                                {{ $row->user->name }}
                            </td>

                            <td class="px-3 py-2 font-semibold text-gray-800">
                                {{ $row->requestable->car_number }}
                            </td>
                            <td class="px-3 py-2 font-semibold text-gray-800">
                                {{ $row->requestable->company }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ $row->car_driver ? $row->car_driver->department->name : 'N/A' }} <br>
                                {{ $row->car_driver ? $row->car_driver->name : 'N/A' }}
                            </td>
                            <td class="px-4 py-4">{{ $row->gate }}</td>
                            {{-- <td class="px-4 py-4">{{ $row->fuel_level }}</td> --}}
                            {{-- <td class="px-4 py-4">{{ $row->destination }}</td> --}}
                            {{-- <td class="px-4 py-4">{{ $row->kilometers }}</td> --}}
                            <td class="px-3 py-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium
                                           bg-blue-100 text-blue-700">
                                    {{ $row->action }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-400 text-sm">
                                No result
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= Material Table ================= -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden">
            <div class="flex justify-between items-center border-b bg-slate-50 px-5 py-3">
                <h1 class="font-semibold text-base text-[#134169]">
                    Latest Material Check-in / Check-out
                </h1>
                <a href="{{ route('material.check') }}" class="text-xs text-[#134169] border border-[#134169] px-3 py-1 rounded-lg
                      hover:bg-[#134169] hover:text-white transition">
                    Show All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-[12px]">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr class="uppercase tracking-wide">
                            <th class="px-3 py-2 text-left font-semibold">ID</th>
                            <th class="px-3 py-2 text-left font-semibold">Date</th>
                            <th class="px-3 py-2 text-left font-semibold">Department</th>
                            <th class="px-3 py-2 text-left font-semibold">Agent</th>
                            <th class="px-3 py-2 text-left font-semibold">Company</th>
                            <th class="px-3 py-2 text-left font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($mat_check_latest as $row)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-3 py-2 font-medium text-gray-800">
                                #{{ $row->requestable->reference }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ $row->created_at }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ $row->user->department->name }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ $row->user->name }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ $row->requestable->company }}
                            </td>
                            <td class="px-3 py-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium
                                           bg-indigo-100 text-indigo-700">
                                    {{ $row->action }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-400 text-sm">
                                No result
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @endif




</div>