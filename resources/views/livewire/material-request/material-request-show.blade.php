<div>
    <main class="p-4 md:p-6 space-y-8 bg-gray-50">

        {{-- Action Buttons --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-700">Material Request Details</h1>
            <div class="flex gap-3">
                <a href="{{ url()->previous() }}"
                    class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium py-2 px-4 rounded text-sm flex items-center gap-2 shadow-sm">
                    ← Back
                </a>
                <button wire:click="download_pdf({{ $MaterialRequest->id }})" wire:loading.attr="disabled"
                    wire:target="download_pdf"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-sm flex items-center gap-2 shadow">
                    <span wire:loading.remove wire:target="download_pdf">📄 Download PDF</span>
                    <span wire:loading wire:target="download_pdf">
                        <span class="iconify lucide--loader size-4 animate-spin"></span> Processing...
                    </span>
                </button>
            </div>
        </div>

        {{-- Request Info --}}
        <section class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Request Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
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
            </div>
        </section>

        {{-- Material Items --}}
        <section class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Requested Items</h2>
            <table class="w-full text-sm border border-gray-300">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Description</th>
                        <th class="p-2 border text-center">Quantity</th>
                        <th class="p-2 border">Serial Number</th>
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
        {{-- Attached Documents --}}
        <section class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Attached Documents</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($MaterialRequest->loadMissing('documents')->documents as $row)
                    <div
                        class="relative bg-gray-100 rounded-md overflow-hidden shadow hover:shadow-lg transition-shadow duration-200">
                        <img src="{{ $row->DocLink() }}" alt="Document image" class="w-full h-48 object-cover" />

                        @if ($MaterialRequest->user_id === Auth::user()->id && $MaterialRequest->isPending())
                            <div class="absolute top-2 right-2 flex gap-2 bg-white/80 p-1 rounded shadow-sm">
                                <x-button-edit href="{{ route('document.edit', ['document' => $row]) }}" />
                                <x-button-delete url="{{ url('document/' . $row->id) }}" />
                            </div>
                        @endif
{{-- 
                        <div class="p-2 text-sm text-gray-700 truncate">
                            {{ basename($row->DocLink()) }}
                            <a href="{{ $row->DocLink() }}" target="_blank"
                                class="block text-blue-600 text-xs underline mt-1">View full</a>
                        </div> --}}
                    </div>
                @endforeach
            </div>
        </section>


        {{-- Approvals --}}
        <section class="bg-white shadow rounded-lg p-5">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 text-center">Approval Signatures</h3>
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
                        <td class="p-2 border">{{ $MaterialRequest->user->department->name }}</td>
                        <td class="p-2 border">
                            {{ $MaterialRequest->hodApproval ? $MaterialRequest->hodApproval->department->name : '—' }}
                        </td>
                        <td class="p-2 border">Head of Department</td>
                        <td class="p-2 border">
                            <x-request-status :status="$MaterialRequest->getStatusFor('hod')" />
                            @if (Auth::user()->canApprove($MaterialRequest) && Auth::user()->isHod())
                                <x-form-request :model="$MaterialRequest" type="material" />
                            @endif
                        </td>
                    </tr>
                    {{-- GM --}}
                    <tr>
                        <td class="p-2 border">{{ $MaterialRequest->user->department->name }}</td>
                        <td class="p-2 border">
                            {{ $MaterialRequest->gmApproval ? $MaterialRequest->gmApproval->department->name : '—' }}
                        </td>
                        <td class="p-2 border">General Manager</td>
                        <td class="p-2 border">
                            <x-request-status :status="$MaterialRequest->getStatusFor('gm')" />
                            @if (Auth::user()->canApprove($MaterialRequest) && Auth::user()->isGm())
                                <x-form-request :model="$MaterialRequest" type="material" />
                            @endif
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
