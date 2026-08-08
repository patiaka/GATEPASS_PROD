<div>
    <main class="p-4 md:p-6 space-y-8 bg-gray-50">

        {{-- Action Buttons --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-700">
                Material Request Details
            </h1>

            <div class="flex gap-3">

                {{-- Back --}}
                <a href="{{ route('material.index') }}"
                    class="inline-flex items-center justify-center w-10 h-10
                  rounded-xl border border-slate-300
                  bg-white text-slate-700
                  hover:bg-slate-800 hover:text-white hover:border-slate-800
                  transition-all duration-200
                  shadow-sm hover:shadow-md"
                    title="Back">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>


                {{-- Download PDF --}}
                <button wire:click="download_pdf({{ $MaterialRequest->id }})" wire:loading.attr="disabled"
                    wire:target="download_pdf"
                    class="relative inline-flex items-center justify-center w-10 h-10
                       rounded-xl border border-[#0e3a61]
                       bg-white text-[#0e3a61]
                       hover:bg-[#0e3a61] hover:text-white
                       disabled:opacity-60 disabled:cursor-not-allowed
                       transition-all duration-200
                       shadow-sm hover:shadow-md"
                    title="Download PDF">

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
                        <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m0 14v1m8-8h1M4 12H3" />
                        </svg>
                    </span>

                </button>

            </div>
        </div>

        {{-- Request Info --}}
        <section class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Request Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <p class="font-medium">Reference</p>
                    <p>#{{ $MaterialRequest->reference }}</p>
                </div>
                <div>
                    <p class="font-medium">Date:</p>
                    <p>{{ $MaterialRequest->created_at }}</p>
                </div>
                <div>
                    <p class="font-medium">Requested By:</p>
                    <p>{{ $MaterialRequest->user->name }}</p>
                </div>
                <div>
                    <p class="font-medium">Department:</p>
                    <p>{{ $MaterialRequest->user->department->name }}</p>
                </div>
                <div>
                    <p class="font-medium">Position:</p>
                    <p>{{ $MaterialRequest->user->poste }}</p>
                </div>

                <div>
                    <p class="font-medium">Delegated Person:</p>
					<p>{{ $MaterialRequest->person_out?->name ?? $MaterialRequest->person_out_name ?? '—' }}</p>

                </div>
            </div>
        </section>

        {{-- Material Items --}}
        <section class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Requested Items</h2>
            <table class="w-full text-sm border border-gray-300">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-2 border text-center">#</th>
                        <th class="p-2 border">Description</th>
                        <th class="p-2 border text-center">Quantity</th>
                        <th class="p-2 border">Additional Info</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($MaterialRequest->loadMissing('material_request_items')->material_request_items as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border text-center">{{ $loop->iteration }}</td>
                            <td class="p-2 border">{{ $row->designation }}</td>
                            <td class="p-2 border text-center">{{ $row->quantity }}</td>
                            <td class="p-2 border">{{ $row->serial_number }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- Attached Documents --}}
        <section class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Attached Documents</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($MaterialRequest->loadMissing('documents')->documents as $row)
                    <div
                        class="relative bg-white rounded-xl border border-gray-200
            overflow-hidden shadow-sm hover:shadow-md
            transition-all duration-200 flex items-center justify-center">

                        <img src="{{ $row->DocLink() }}" alt="Document image"
                            class="w-full max-h-56 object-contain bg-white p-2" />



                        @if ($MaterialRequest->user_id === Auth::user()->id && $MaterialRequest->isPending())
                            <div class="absolute top-2 right-2 flex gap-2 bg-white/80 p-1 rounded shadow-sm">
                                <x-button-edit href="{{ route('document.edit', ['document' => $row]) }}" />
                                <x-button-delete url="{{ url('document/' . $row->id) }}" />
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>


        {{-- Approvals --}}
        <section class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center justify-between mb-4 border-b pb-2">

                <div class="w-1/3"></div>

                <h3 class="text-lg font-semibold text-gray-800 text-center w-1/3">
                    Approval Signatures
                </h3>

                <div class="w-1/3 flex justify-end">
                    @if (Auth::user()->canApprove($MaterialRequest) && Auth::user()->isApprover())
                        <x-form-request :model="$MaterialRequest" type="material" />
                    @endif
                </div>

            </div>
            <table class="w-full text-sm border border-gray-300 text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Department</th>
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Position</th>
                        <th class="p-2 border">Signature</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Applicant --}}
                    <tr>
                        <td class="p-2 border">{{ $MaterialRequest->user->department->name }}</td>
                        <td class="p-2 border">{{ $MaterialRequest->user->name }}</td>
                        <td class="p-2 border">{{ $MaterialRequest->user->poste }}</td>
                        <td class="p-2 border">✅ Approved</td>
                    </tr>
                    
                    {{-- HOD --}}
                    <tr>
                        <td class="p-2 border">
                            {{ $MaterialRequest->hodApproval ? $MaterialRequest->hodApproval->department->name : '—' }}
                        </td>
                        <td class="p-2 border">
                            {{ $MaterialRequest->hodApproval ? $MaterialRequest->hodApproval->name : '—' }}
                        </td>
                        <td class="p-2 border">
                            {{ $MaterialRequest->hodApproval ? $MaterialRequest->hodApproval->poste : '—' }}
                        </td>
                        <td class="p-2 border">
                            <x-request-status :status="$MaterialRequest->getStatusFor('hod')" />
                            {{-- @if (Auth::user()->canApprove($MaterialRequest) && Auth::user()->isHod())
                            <x-form-request :model="$MaterialRequest" type="material" />
                            @endif --}}
                        </td>
                    </tr>

                    {{-- Director --}}
                    @if ($MaterialRequest->isRequiredDirectorApproval())
                    <tr>
                        <td class="p-2 border">
                            {{ $MaterialRequest->directorApproval ? $MaterialRequest->directorApproval->department->name : '—' }}
                        </td>
                        <td class="p-2 border">
                            {{ $MaterialRequest->directorApproval ? $MaterialRequest->directorApproval->name : '—' }}
                        </td>
                        <td class="p-2 border">
                            {{ $MaterialRequest->directorApproval ? $MaterialRequest->directorApproval->poste : '—' }}
                        </td>
                        <td class="p-2 border">
                            <x-request-status :status="$MaterialRequest->getStatusFor('director')" />
                            {{-- @if (Auth::user()->canApprove($MaterialRequest) && Auth::user()->isDirector())
                            <x-form-request :model="$MaterialRequest" type="material" />
                            @endif --}}
                        </td>
                    </tr>
                    @endif

                    {{-- GM --}}
                    <tr>
                        <td class="p-2 border">
                            {{ $MaterialRequest->gmApproval ? $MaterialRequest->gmApproval->department->name : '—' }}
                        </td>
                        <td class="p-2 border">
                            {{ $MaterialRequest->gmApproval ? $MaterialRequest->gmApproval->name : '-' }}
                        </td>
                        <td class="p-2 border">
                            {{ $MaterialRequest->gmApproval ? $MaterialRequest->gmApproval->poste : '-' }}
                        </td>
                        <td class="p-2 border">
                            <x-request-status :status="$MaterialRequest->getStatusFor('gm')" />
                            {{-- @if (Auth::user()->canApprove($MaterialRequest) && Auth::user()->isGm())
                            <x-form-request :model="$MaterialRequest" type="material" />
                            @endif --}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        {{-- Notes --}}
        <section class="text-xs text-gray-600 mt-6">
            <p class="font-semibold">Notes:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Items may be removed from site on specified dates. Up to seven days can be nominated for multiple
                    entries/exits.</li>
                <li>Final approval must come from the designated General Manager depending on the department.</li>
            </ul>
        </section>

    </main>
</div>
