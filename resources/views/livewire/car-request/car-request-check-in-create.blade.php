<div>
    <div class="mx-auto p-6">
        <div class="bg-gradient-to-b from-white to-slate-50 rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- LEFT COLUMN: FORM --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">

                    {{-- Header --}}
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-[#0f4b73] tracking-tight">
                            Check-In Response Form
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Vehicle entry / exit inspection record
                        </p>
                    </div>

                    <form wire:submit.prevent="recordSecurityCheck" class="space-y-6">

                        {{-- Gate pass --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Gate Pass
                            </label>

                            <x-select2 :options="$carRequests" wire:model="car_request_id" name="car_request_id"
                                placeholder="Select gate pass" optionLabel="full_name" />

                            @error('car_request_id')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($carRequest && $carRequest->somisy_car != 'no_vehicle')
                            {{-- Driver --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Driver
                                </label>

                                <x-select wire:model="car_driver_id" name="car driver">
                                    @foreach ($car_driver_list as $row)
                                        <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                    @endforeach
                                </x-select>

                                @error('car_driver_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    Kilometers <span class="text-red-500">*</span>
                                </label>

                                <div class="grid grid-cols-2 gap-3">
                                    @foreach (['Kilometers', 'Per hours'] as $g)
                                        <label
                                            class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50
                              hover:bg-blue-50 hover:border-[#134169] cursor-pointer transition">
                                            <input type="radio" wire:model.live="Kilometers_type"
                                                value="{{ $g }}"
                                                class="mt-1 text-[#134169] focus:ring-[#134169]">

                                            <div>
                                                <div class="font-semibold text-slate-800">{{ $g }}</div>
                                                {{-- <div class="text-xs text-slate-500">Kilometers_type</div> --}}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                @error('Kilometers_type')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kilometers --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    {{ $Kilometers_type === 'Per hours' ? 'Per hours' : 'Kilometers' }} <span
                                        class="text-red-500">*</span>
                                </label>

                                <input type="number" wire:model="kilometers"
                                    class="w-1/2 rounded-xl border border-gray-300 bg-gray-50 px-4 py-2
                          focus:ring-2 focus:ring-[#134169] focus:border-transparent outline-none">

                                @error('kilometers')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif


                        {{-- Gate (RADIO BLOCKS) --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">
                                Gate <span class="text-red-500">*</span>
                            </label>

                            <div class="grid grid-cols-2 gap-3">
                                @foreach (['Front', 'Back'] as $g)
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

                        @if ($carRequest && $carRequest->somisy_car != 'no_vehicle')
                            {{-- Fuel Level (RADIO BLOCKS) --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    Fuel Level <span class="text-red-500">*</span>
                                </label>

                                <div class="grid grid-cols-2 gap-3">
                                    @foreach (['25%', '50%', '75%', '100%'] as $f)
                                        <label
                                            class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50
                              hover:bg-blue-50 hover:border-[#134169] cursor-pointer transition">
                                            <input type="radio" wire:model="fuel_level" value="{{ $f }}"
                                                class="mt-1 text-[#134169] focus:ring-[#134169]">

                                            <div>
                                                <div class="font-semibold text-slate-800">{{ $f }}</div>
                                                <div class="text-xs text-slate-500">Fuel level</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                @error('fuel_level')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
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

                        {{-- Decision --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Decision <span class="text-red-500">*</span>
                            </label>

                            <select wire:model="decision"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2
                       focus:ring-2 focus:ring-[#134169] focus:border-transparent outline-none">
                                <option value="">Select decision</option>

                                @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                                    @continue(in_array($row->value, ['Progress', 'Pending', 'Expired']))
                                    <option value="{{ $row }}">{{ $row }}</option>
                                @endforeach
                            </select>

                            @error('decision')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">

                            <x-form-action cancel="car.check" target="recordSecurityCheck" />

                        </div>

                    </form>
                </div>


                {{-- RIGHT COLUMN: CAR REQUEST DETAILS --}}
                <div class="relative">
                    {{-- LOADER --}}
                    <div wire:loading wire:target="car_request_id"
                        class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-20 rounded-2xl">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="animate-spin h-12 w-12 text-[#134169]" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                            <p class="text-sm text-slate-600">Loading request details...</p>
                        </div>
                    </div>

                    @if ($carRequest)
                        <div class="bg-white rounded-2xl shadow-inner border border-gray-100 p-6" wire:loading.remove
                            wire:target="car_request_id">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-800">Gate Pass Details</h2>
                                    <p class="text-xs text-slate-500 mt-1">Détails et approbations</p>
                                </div>

                                <div>
                                    <span @class([
                                        'inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium',
                                        'bg-blue-500 text-white' => $carRequest->isApproved(),
                                    ])>
                                        {{ $carRequest->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 overflow-x-auto">
                                <table class="w-full text-sm">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr class="py-2">
                                            <th class="text-left py-3 pr-4 text-slate-600 w-1/3">Company</th>
                                            <td class="py-3">{{ $carRequest->company }}</td>
                                        </tr>

                                        <tr class="bg-gray-50">
                                            <th class="text-left py-3 pr-4 text-slate-600">Somisy Vehicle
                                            </th>
                                            <td class="py-3 text-uppercase">{{ $carRequest->somisy_car }}</td>
                                        </tr>

                                        <tr>
                                            <th class="text-left py-3 pr-4 text-slate-600">Camp Resident</th>
                                            <td class="py-3 text-uppercase">{{ $carRequest->resident }}</td>
                                        </tr>


                                        {{-- Drivers --}}
                                        @foreach ($carRequest->car_drivers as $row)
                                            <tr>
                                                <th class="text-left py-3 pr-4 text-slate-600">Driver Name</th>
                                                <td class="py-3">{{ $row->user->name }}</td>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th class="text-left py-3 pr-4 text-slate-600">Driver Phone</th>
                                                <td class="py-3">{{ $row->user->contact }}</td>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th class="text-left py-3 pr-4 text-slate-600">Driver Badge Number</th>
                                                <td class="py-3">{{ $row->user->badge_number }}</td>
                                            </tr>
                                        @endforeach


                                        <tr class="bg-gray-50">
                                            <th class="text-left py-3 pr-4 text-slate-600">Vehicle Type</th>
                                            <td class="py-3">{{ $carRequest->car_type }}</td>
                                        </tr>

                                        <tr>
                                            <th class="text-left py-3 pr-4 text-slate-600">Vehicle Number</th>
                                            <td class="py-3">{{ $carRequest->car_number }}</td>
                                        </tr>


                                        <tr>
                                            <th class="text-left py-3 pr-4 text-slate-600">Valid From</th>
                                            <td class="py-3">{{ $carRequest->start }}</td>
                                        </tr>

                                        <tr class="bg-gray-50">
                                            <th class="text-left py-3 pr-4 text-slate-600">Valid Until</th>
                                            <td class="py-3">{{ $carRequest->end }}</td>
                                        </tr>

                                        <tr>
                                            <th class="text-left py-3 pr-4 text-slate-600">Departure Time</th>
                                            <td class="py-3">{{ $carRequest->depart_at }}</td>
                                        </tr>

                                        <tr class="bg-gray-50">
                                            <th class="text-left py-3 pr-4 text-slate-600">Arrival Time</th>
                                            <td class="py-3">{{ $carRequest->arrive_at }}</td>
                                        </tr>

                                        <tr>
                                            <th class="text-left py-3 pr-4 text-slate-600">Destination</th>
                                            <td class="py-3">{{ $carRequest->destination }}</td>
                                        </tr>

                                        <tr class="bg-gray-50">
                                            <th class="text-left py-3 pr-4 text-slate-600">Reason</th>
                                            <td class="py-3">{{ $carRequest->reason }}</td>
                                        </tr>

                                        {{-- Passengers --}}
                                        @foreach ($carRequest->passengers as $row)
                                            <tr>
                                                <th class="text-left py-3 pr-4 text-slate-600">Passenger</th>
                                                <td class="py-3">{{ $row->user->name }}</td>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th class="text-left py-3 pr-4 text-slate-600">Phone</th>
                                                <td class="py-3">{{ $row->user->contact }}</td>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th class="text-left py-3 pr-4 text-slate-600"> Badge Number</th>
                                                <td class="py-3">{{ $row->user->badge_number }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            <h3 class="mt-6 text-md font-semibold text-slate-800">Approvals</h3>
                            <div class="bg-white rounded-lg mt-3 shadow-sm border border-gray-100 p-4">
                                <table class="w-full text-sm table-auto">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="w-10 text-left px-4 py-2 font-semibold text-slate-700">#</th>
                                            <th class="w-10 text-left px-4 py-2 font-semibold text-slate-700">Name</th>
                                            <th class="w-1/3 text-left px-4 py-2 font-semibold text-slate-700">Approver
                                                Position</th>
                                            <th class="text-left px-4 py-2 font-semibold text-slate-700">Status</th>
                                            <th class="text-left px-4 py-2 font-semibold text-slate-700">Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <td class="px-4 py-3">1</td>
                                            <td class="px-4 py-3">
                                                {{ $carRequest->hodApproval ? $carRequest->hodApproval->name : '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $carRequest->hodApproval ? $carRequest->hodApproval->poste : 'HOD' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <x-request-status :status="$carRequest->getStatusFor('hod')" />
                                            </td>
                                            <td class="px-4 py-3 text-slate-600">{{ $carRequest->hod_comment }}</td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-3">2</td>
                                            <td class="px-4 py-3">
                                                {{ $carRequest->gmApproval ? $carRequest->gmApproval->name : '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $carRequest->gmApproval ? $carRequest->gmApproval->poste : 'GM' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <x-request-status :status="$carRequest->getStatusFor('gm')" />
                                            </td>
                                            <td class="px-4 py-3 text-slate-600">{{ $carRequest->gm_comment }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic mt-4" wire:loading.remove
                            wire:target="car_request_id">
                            Select a gate pass to preview its information.
                        </p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
