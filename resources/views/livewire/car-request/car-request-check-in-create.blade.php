<div>
    <div class="mx-auto p-3 sm:p-6">
        <div class="bg-gradient-to-b from-white to-slate-50 rounded-2xl shadow-lg border border-gray-100 p-3 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8">

                {{-- LEFT COLUMN: FORM --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 sm:p-6 lg:p-8">

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

                            @if ($car_request_id)
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
                        @php
                            $sv = $carRequest->status->value;
                            $sStyles = [
                                'Pending' => ['bg-orange-50 text-orange-700 ring-orange-200', 'bg-orange-500'],
                                'Progress' => ['bg-yellow-50 text-yellow-700 ring-yellow-200', 'bg-yellow-500'],
                                'Approved' => ['bg-emerald-50 text-emerald-700 ring-emerald-200', 'bg-emerald-500'],
                                'Rejected' => ['bg-rose-50 text-rose-700 ring-rose-200', 'bg-rose-500'],
                                'Expired' => ['bg-slate-100 text-slate-600 ring-slate-200', 'bg-slate-400'],
                            ];
                            [$sBadge, $sDot] = $sStyles[$sv] ?? $sStyles['Pending'];
                            $fmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d-m-Y H:i') : null;
                            $fmtDate = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d-m-Y') : '—';
                            $stateOf = function (string $actor) use ($carRequest) {
                                $l = $carRequest->getStatusFor($actor)[0] ?? '';
                                return str_contains($l, 'Approved') ? 'approved' : (str_contains($l, 'Rejected') ? 'rejected' : 'pending');
                            };
                            $steps = [
                                ['role' => 'HOD', 'name' => $carRequest->hodApproval?->name, 'date' => $fmt($carRequest->hod_approval_date), 'comment' => $carRequest->hod_comment, 'state' => $stateOf('hod')],
                            ];
                            if ($carRequest->isRequiredDirectorApproval()) {
                                $steps[] = ['role' => 'Director', 'name' => $carRequest->directorApproval?->name, 'date' => $fmt($carRequest->director_approval_date), 'comment' => $carRequest->director_comment, 'state' => $stateOf('director')];
                            }
                            $steps[] = ['role' => 'General Manager', 'name' => $carRequest->gmApproval?->name, 'date' => $fmt($carRequest->gm_approval_date), 'comment' => $carRequest->gm_comment, 'state' => $stateOf('gm')];
                            $hasVehicle = $carRequest->somisy_car !== 'no_vehicle';
                        @endphp

                        <div class="bg-white rounded-2xl shadow-inner border border-gray-100 p-6" wire:loading.remove
                            wire:target="car_request_id">

                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-4 pb-4 border-b border-gray-100">
                                <div>
                                    <h2 class="text-lg font-semibold text-[#134169]">Gate Pass Details</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">#{{ $carRequest->reference }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 {{ $sBadge }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sDot }}"></span>
                                    {{ $sv }}
                                </span>
                            </div>

                            {{-- Info grid --}}
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm mt-4">
                                <div>
                                    <dt class="text-xs text-slate-500">Company</dt>
                                    <dd class="text-slate-800 font-medium">{{ $carRequest->company }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-500">Somisy vehicle</dt>
                                    <dd class="text-slate-800 uppercase">{{ $carRequest->somisy_car }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-500">Camp resident</dt>
                                    <dd class="text-slate-800 uppercase">{{ $carRequest->resident }}</dd>
                                </div>
                                @if ($hasVehicle)
                                    <div>
                                        <dt class="text-xs text-slate-500">Vehicle type</dt>
                                        <dd class="text-slate-800">{{ $carRequest->car_type ?: '—' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">Vehicle number</dt>
                                        <dd class="text-slate-800 font-medium">{{ $carRequest->car_number ?: '—' }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-xs text-slate-500">Valid from</dt>
                                    <dd class="text-slate-800">{{ $fmtDate($carRequest->start) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-500">Valid until</dt>
                                    <dd class="text-slate-800">{{ $fmtDate($carRequest->end) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-500">Departure time</dt>
                                    <dd class="text-slate-800">{{ $carRequest->depart_at }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-500">Arrival time</dt>
                                    <dd class="text-slate-800">{{ $carRequest->arrive_at }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-xs text-slate-500">Destination</dt>
                                    <dd class="text-slate-800">{{ $carRequest->destination }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-xs text-slate-500">Reason</dt>
                                    <dd class="text-slate-800">{{ $carRequest->reason }}</dd>
                                </div>
                            </dl>

                            {{-- Drivers --}}
                            @if ($carRequest->car_drivers->isNotEmpty())
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-5 mb-2 pt-4 border-t">Drivers</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($carRequest->car_drivers as $row)
                                        <div class="flex items-center gap-3 rounded-lg border border-gray-100 bg-slate-50/60 px-3 py-2">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#134169]/10 text-[#134169] text-xs font-bold">
                                                {{ \Illuminate\Support\Str::of($row->user?->name)->substr(0, 1)->upper() }}
                                            </span>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-slate-800 truncate">{{ $row->user?->name ?? '—' }}</p>
                                                <p class="text-xs text-slate-500">{{ $row->user?->contact ?? '—' }} · {{ $row->user?->badge_number ?? '—' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Passengers --}}
                            @if ($carRequest->passengers->isNotEmpty())
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-5 mb-2 pt-4 border-t">Residents / passengers</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($carRequest->passengers as $row)
                                        <div class="flex items-center gap-3 rounded-lg border border-gray-100 bg-slate-50/60 px-3 py-2">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#134169]/10 text-[#134169] text-xs font-bold">
                                                {{ \Illuminate\Support\Str::of($row->user?->name)->substr(0, 1)->upper() }}
                                            </span>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-slate-800 truncate">{{ $row->user?->name ?? '—' }}</p>
                                                <p class="text-xs text-slate-500">{{ $row->user?->contact ?? '—' }} · {{ $row->user?->badge_number ?? '—' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Approval workflow --}}
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-5 mb-3 pt-4 border-t">Approval workflow</p>
                            <ol class="relative border-l border-gray-200 ml-2 space-y-5">
                                @foreach ($steps as $step)
                                    @php
                                        $d = match ($step['state']) { 'approved' => 'bg-emerald-500', 'rejected' => 'bg-rose-500', default => 'bg-slate-300' };
                                        $b = match ($step['state']) {
                                            'approved' => ['Approved', 'text-emerald-700 bg-emerald-50 ring-emerald-200'],
                                            'rejected' => ['Rejected', 'text-rose-700 bg-rose-50 ring-rose-200'],
                                            default => ['Pending', 'text-slate-500 bg-slate-50 ring-slate-200'],
                                        };
                                    @endphp
                                    <li class="ml-5">
                                        <span class="absolute -left-[7px] w-3.5 h-3.5 rounded-full ring-4 ring-white {{ $d }}"></span>
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-sm font-semibold text-slate-700">{{ $step['role'] }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold ring-1 {{ $b[1] }}">{{ $b[0] }}</span>
                                        </div>
                                        @if ($step['name'])
                                            <p class="text-xs text-slate-600 mt-0.5">{{ $step['name'] }}</p>
                                        @endif
                                        @if ($step['date'])
                                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $step['date'] }}</p>
                                        @endif
                                        @if ($step['comment'])
                                            <p class="text-xs text-slate-500 mt-1 italic bg-slate-50 rounded-md px-2 py-1">“{{ $step['comment'] }}”</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
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
