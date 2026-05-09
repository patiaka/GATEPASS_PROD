<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-4 gap-4">
        <div>
            <h1 class="font-semibold text-2xl text-[#134169]">Resident & Vehicle Off Site Details</h1>
            <div class="flex items-center mt-2 text-sm text-slate-600">
                <span class="whitespace-nowrap">#Request ID {{ $carRequest->reference }} | Status:</span>

                <span @class([
                    'inline-flex items-center justify-center w-4 h-4 rounded-full shadow-sm -mt-0.5 ml-2 shrink-0',
                    'bg-red-500 border-red-500' =>
                        $carRequest->isRejected() || $carRequest->isExpired(),
                    'bg-orange-200 border-orange-200' => $carRequest->isPending(),
                    'bg-yellow-400 border-yellow-400' => $carRequest->isProgress(),
                    'bg-green-400 border-green-400' => $carRequest->isApproved() ?? false, // non requis mais utile si
                ])>
                </span>

                <span class="text-sm font-medium text-slate-800 ml-1">{{ $carRequest->status }}</span>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('car.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
          border border-[#0e3a61]
          bg-white
          text-[#0e3a61] text-sm font-medium
          hover:bg-[#0e3a61] hover:text-white
          transition shadow-sm
          focus:outline-none focus:ring-2 focus:ring-[#0e3a61]/40">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>

                Back to list
            </a>

            <a href="{{ route('car.edit', ['CarRequest' => $carRequest]) }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl
          border border-blue-200 bg-blue-50
          text-blue-700
          hover:bg-blue-100 hover:border-blue-300
          transition shadow-sm
          focus:outline-none focus:ring-2 focus:ring-blue-400/30"
                aria-label="Edit request">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16.862 4.487l1.687 1.687M7 17l4-1 9-9a2.121 2.121 0 00-3-3l-9 9-1 4z" />
                </svg>
            </a>


            <button wire:click="download_pdf({{ $carRequest }})" wire:loading.attr="disabled"
                wire:target="download_pdf"
                class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl
               bg-[#0e3a61] text-white
               hover:bg-[#0c3253]
               disabled:opacity-60 disabled:cursor-not-allowed
               transition shadow-md
               focus:outline-none focus:ring-2 focus:ring-[#0e3a61]/40"
                aria-label="Download PDF">

                {{-- icon --}}
                <span wire:loading.remove wire:target="download_pdf">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12" />
                    </svg>
                </span>

                {{-- loading --}}
                <span wire:loading wire:target="download_pdf">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-spin" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m0 14v1m8-8h1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m0 12.728l.707-.707M17.657 6.343l.707-.707" />
                    </svg>
                </span>
            </button>

        </div>
    </div>

    {{-- Details card --}}
    <div class="bg-white rounded-2xl mt-4 shadow-sm border border-gray-100 p-6">
        <div class="relative">

            {{-- APPROVED STAMP CENTER --}}
            @if ($carRequest->isApproved())
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">

                    <div
                        class="
                rotate-[-15deg]
                border-[6px] border-green-400
                text-green-400
                text-3xl font-extrabold
                px-10 py-4
                opacity-25
                tracking-widest
                rounded-lg
                select-none
            ">
                        APPROVED
                    </div>

                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <tr class="bg-gray-50">
                            <th class="text-left py-3 pr-4 text-slate-600 w-1/3">Company</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->company }}</td>
                        </tr>
                        <tr>
                            <th class="text-left py-3 pr-4 text-slate-600">Department</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->user->department->name }}</td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="text-left py-3 pr-4 text-slate-600">Somisy Vehicle</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->somisy_car }}</td>
                        </tr>

                        <tr>
                            <th class="text-left py-3 pr-4 text-slate-600">Camp Resident</th>
                            <td class="py-3 text-slate-800">
                                {{ $carRequest->resident }}
                            </td>
                        </tr>
                        @if ($carRequest->car_drivers->isNotEmpty())
                            @foreach ($carRequest->car_drivers as $row)
                                <tr class="bg-gray-50">
                                    <th class="text-left py-3 pr-4 text-slate-600">Driver Name</th>
                                    <td class="py-3 text-slate-800">{{ $row->user->name }}</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="text-left py-3 pr-4 text-slate-600">Phone</th>
                                    <td class="py-3 text-slate-800">{{ $row->user->contact }}</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="text-left py-3 pr-4 text-slate-600">Badge Number</th>
                                    <td class="py-3 text-slate-800">{{ $row->user->badge_number }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th class="text-left py-3 pr-4 text-slate-600">Vehicle Type</th>
                                <td class="py-3 text-slate-800">{{ $carRequest->car_type }}</td>
                            </tr>

                            <tr class="bg-gray-50">
                                <th class="text-left py-3 pr-4 text-slate-600">Vehicle Number</th>
                                <td class="py-3 text-slate-800">{{ $carRequest->car_number }}</td>
                            </tr>
                        @endif

                        <tr>
                            <th class="text-left py-3 pr-4 text-slate-600">Date Valid From</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->start }}</td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="text-left py-3 pr-4 text-slate-600">Date Until</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->end }}</td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="text-left py-3 pr-4 text-slate-600">Justification</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->comment ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <th class="text-left py-3 pr-4 text-slate-600">Departure Time</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->depart_at }}</td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="text-left py-3 pr-4 text-slate-600">Arrival Time</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->arrive_at }}</td>
                        </tr>

                        <tr>
                            <th class="text-left py-3 pr-4 text-slate-600">Destinations</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->destination }}</td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="text-left py-3 pr-4 text-slate-600">Reason for Travel</th>
                            <td class="py-3 text-slate-800">{{ $carRequest->reason }}</td>
                        </tr>
                        @if ($carRequest->passengers->isNotEmpty())
                            @foreach ($carRequest->passengers as $row)
                                <tr>
                                    <th class="text-left py-3 pr-4 text-slate-600">Resident Name</th>
                                    <td class="py-3 text-slate-800">{{ $row->user->name }}</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="text-left py-3 pr-4 text-slate-600">Phone</th>
                                    <td class="py-3 text-slate-800">{{ $row->user->contact }}</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="text-left py-3 pr-4 text-slate-600">Badge Number</th>
                                    <td class="py-3 text-slate-800">{{ $row->user->badge_number }}</td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Approvals --}}
    <div class="bg-white rounded-2xl mt-6 shadow-sm border border-gray-100 p-4">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b pb-3 mb-4">
            <h2 class="text-lg font-semibold text-slate-800">
                Approvals
            </h2>

            {{-- Submit Response Button (top right) --}}
            @if (Auth::user()->canApprove($carRequest) && Auth::user()->isApprover())
                <x-form-request :model="$carRequest" type="vehicle" />
            @endif
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-12 text-left px-4 py-2 font-semibold text-gray-700">#</th>
                        <th class="w-1/4 text-left px-4 py-2 font-semibold text-gray-700">Name</th>
                        <th class="w-1/4 text-left px-4 py-2 font-semibold text-gray-700">Approver Position</th>
                        <th class="w-1/4 text-left px-4 py-2 font-semibold text-gray-700">Status</th>
                        <th class="text-left px-4 py-2 font-semibold text-gray-700">Comments</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @php
                        $index = 1;
                    @endphp
                    {{-- HOD --}}
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $index++ }}</td>
                        <td class="px-4 py-3 font-medium">
                            {{ $carRequest->hodApproval ? $carRequest->hodApproval->name : '—' }}</td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                {{ $carRequest->hodApproval ? $carRequest->hodApproval->poste : 'HOD' }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <x-request-status :status="$carRequest->getStatusFor('hod')" />
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $carRequest->hod_comment ?? '—' }}
                        </td>
                    </tr>

                    {{-- Director --}}
                    @if ($carRequest->isRequiredDirectorApproval())
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $index++ }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-2">
                                {{ $carRequest->directorApproval ? $carRequest->directorApproval->name : '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                {{ $carRequest->directorApproval ? $carRequest->directorApproval->poste : 'DIRECTOR' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <x-request-status :status="$carRequest->getStatusFor('director')" />
                            {{-- @if (Auth::user()->canApprove($MaterialRequest) && Auth::user()->isDirector())
                            <x-form-request :model="$MaterialRequest" type="material" />
                            @endif --}}
                        </td>
                    </tr>
                    @endif

                    {{-- GM --}}
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $index++ }}</td>
                        <td class="px-4 py-3 font-medium">
                            {{ $carRequest->gmApproval ? $carRequest->gmApproval->name : '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                {{ $carRequest->gmApproval ? $carRequest->gmApproval->poste : 'GM' }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <x-request-status :status="$carRequest->getStatusFor('gm')" />
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $carRequest->gm_comment ?? '—' }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
    {{-- =================== END APPROVALS =================== --}}
</div>
