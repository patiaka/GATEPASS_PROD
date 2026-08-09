<div>
    <div class="card bg-white shadow-sm p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">

                {{-- Header --}}
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-[#0f4b73] tracking-tight">
                        Material Request Check-In / Check-Out
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Material gate movement record
                    </p>
                </div>

                <form wire:submit.prevent="recordSecurityCheck" class="space-y-6">

                    {{-- Material Request --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Material Request
                        </label>

                        <x-select2 :options="$materialRequests" optionLabel="full_name" wire:model="material_request_id"
                            name="material_request_id" placeholder="Select material request" />

                        @error('material_request_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror

                        @if ($material_request_id)
                            @if ($last_movement)
                                <p class="text-xs text-slate-500 mt-2">
                                    Last movement: <span class="font-semibold text-[#134169]">{{ $last_movement }}</span>
                                    — suggested action preselected.
                                </p>
                            @else
                                <p class="text-xs text-slate-500 mt-2">
                                    No previous movement — <span class="font-semibold text-[#134169]">Exit</span> preselected.
                                </p>
                            @endif
                        @endif
                    </div>

                    {{-- Gate (RADIO CARDS — SAME STYLE) --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            Gate <span class="text-red-500">*</span>
                        </label>

                        <div class="grid grid-cols-3 gap-3">
                            @foreach (['Front', 'Back', 'Airport'] as $g)
                                <label
                                    class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50
                              hover:bg-blue-50 hover:border-[#134169] cursor-pointer transition">
                                    <input type="radio" wire:model="gate" value="{{ $g }}"
                                        class="mt-1 text-[#134169] focus:ring-[#134169]">

                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $g }}</div>
                                        <div class="text-xs text-slate-500">Security gate</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @error('gate')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Action --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Action <span class="text-red-500">*</span>
                        </label>

                        <select wire:model="action"
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2
                       focus:ring-2 focus:ring-[#134169] focus:border-transparent outline-none">
                            <option value="">Select action</option>
                            <option value="Exit">Exit</option>
                            <option value="Entry">Entry</option>
                        </select>

                        @error('action')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">

                        <x-form-action cancel="material.check" target="recordSecurityCheck" />

                    </div>

                </form>
            </div>




            {{-- RIGHT COLUMN: MATERIAL REQUEST DETAILS --}}
            <div class="relative">
                {{-- LOADER --}}
                <div wire:loading wire:target="material_request_id"
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-20">

                    <div class="flex flex-col items-center gap-2">
                        <svg class="animate-spin h-10 w-10 text-[#134169]" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <p class="text-sm text-gray-600">Loading request details...</p>
                    </div>
                </div>
                @if ($materialRequest)
                    <div class="bg-white rounded shadow border p-6" wire:loading.remove
                        wire:target="material_request_id">

                        {{-- Header --}}
                        <h2 class="text-lg font-semibold mb-4">
                            Material Request Details #{{ $materialRequest->reference }}
                            <span @class([
                                'px-2 py-1 text-xs font-medium text-white rounded-full',
                                'bg-green-600' => $materialRequest->isApproved(),
                                'bg-red-600' => $materialRequest->isRejected(),
                                'bg-yellow-600' => $materialRequest->isPending(),
                            ])>
                                {{ $materialRequest->status }}
                            </span>
                        </h2>

                        {{-- REQUEST INFO --}}
                        <table class="w-full border-2 border-black border-collapse mb-6">
                            <tbody>
                                <tr>
                                    <th class="w-1/5 border-2 border-black bg-gray-100 p-2 text-left">Date</th>
                                    <td class="border-2 border-black p-2">{{ $materialRequest->created_at }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/5 border-2 border-black bg-gray-100 p-2 text-left">Updated</th>
                                    <td class="border-2 border-black p-2">{{ $materialRequest->updated_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/5 border-2 border-black bg-gray-100 p-2 text-left">Requester</th>
                                    <td class="border-2 border-black p-2">{{ $materialRequest->user->name }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- MATERIAL ITEMS --}}
                        <h3 class="font-semibold mb-2">Material Items</h3>

                        <table class="w-full border-2 border-black border-collapse">
                            <thead>
                                <tr class="[&>th]:border-2 [&>th]:border-black [&>th]:bg-gray-100 [&>th]:p-2 text-left">
                                    <th class="w-10">#</th>
                                    <th>DESCRIPTION / DESIGNATION</th>
                                    <th class="w-24">QUANTITY</th>
                                    <th class="w-40">ADDITIONAL INFORMATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materialRequest->material_request_items as $row)
                                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->designation }}</td>
                                        <td>{{ $row->quantity }}</td>
                                        <td>{{ $row->serial_number }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Approvals --}}
                        <section class="bg-white shadow rounded-lg p-5">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 text-center">Approval
                                Signatures</h3>
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
                                        <td class="p-2 border">{{ $materialRequest->user->department->name }}</td>
                                        <td class="p-2 border">{{ $materialRequest->user->name }}</td>
                                        <td class="p-2 border">{{ $materialRequest->user->poste }}</td>
                                        <td class="p-2 border">✅ Approved</td>
                                    </tr>
                                    {{-- HOD --}}
                                    <tr>
                                        <td class="p-2 border">{{ $materialRequest->user->department->name }}</td>
                                        <td class="p-2 border">
                                            {{ $materialRequest->hodApproval ? $materialRequest->hodApproval->name : '—' }}
                                        </td>
                                        <td class="p-2 border">
                                            {{ $materialRequest->hodApproval ? $materialRequest->hodApproval->poste : '—' }}
                                        </td>
                                        <td class="p-2 border">
                                            <x-request-status :status="$materialRequest->getStatusFor('hod')" />

                                        </td>
                                    </tr>
                                    {{-- GM --}}
                                    <tr>
                                        <td class="p-2 border">
                                            {{ $materialRequest->gmApproval ? $materialRequest->gmApproval->department->name : '—' }}
                                        </td>
                                        <td class="p-2 border">
                                            {{ $materialRequest->gmApproval ? $materialRequest->gmApproval->name : '-' }}
                                        </td>
                                        <td class="p-2 border">
                                            {{ $materialRequest->gmApproval ? $materialRequest->gmApproval->poste : '—' }}
                                        </td>
                                        <td class="p-2 border">
                                            <x-request-status :status="$materialRequest->getStatusFor('gm')" />

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>

                        {{-- Attached Documents --}}
                        <section class="bg-white shadow rounded-lg p-5">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Attached Documents</h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($materialRequest->loadMissing('documents')->documents as $row)
                                    <div
                                        class="relative bg-white rounded-xl border border-gray-200
            overflow-hidden shadow-sm hover:shadow-md
            transition-all duration-200 flex items-center justify-center">

                                        <img src="{{ $row->DocLink() }}" alt="Document image"
                                            class="w-full max-h-56 object-contain bg-white p-2" />

                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                @else
                    <p class="text-gray-500 italic mt-4">Select a material request to preview its details.</p>
                @endif
            </div>

        </div>
    </div>

</div>
