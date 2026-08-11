<div class="mx-auto">
    <div class="flex items-center justify-between pb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-[#0e3a61] tracking-tight flex items-center gap-2">
            Resident & Vehicle Off Site Form
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1
                text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                New
            </span>
        </h1>

        <a wire:navigate href="{{ route('car.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-[#0e3a61] text-white text-sm font-medium
              hover:bg-[#0c3253] shadow-sm transition
              focus:outline-none focus:ring-2 focus:ring-white/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to list
        </a>
    </div>

    <form wire:submit="save" class="mt-6">
        @php
            $pill = 'inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-gray-50 cursor-pointer text-sm font-medium text-slate-700 transition hover:border-[#134169]/50 has-[:checked]:border-[#134169] has-[:checked]:bg-[#134169]/5 has-[:checked]:text-[#134169] has-[:checked]:ring-1 has-[:checked]:ring-[#134169]';
            $reqStar = 'after:content-[\'*\'] after:ml-0.5 after:text-red-500';
        @endphp
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8 space-y-8">

            <p class="text-xs text-slate-400 -mt-2">Fields marked <span class="text-red-500 font-semibold">*</span> are required.</p>

            <!-- Company -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">Requestor
                    Company</label>
                <div class="md:col-span-9">
                    <input type="text" name="form.company" required wire:model="form.company"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition ">
                    @error('form.company')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Somisy Vehicle -->
            <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                <label
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">Somisy
                    Vehicle</label>
                <div class="md:col-span-9">
                    <div class="flex flex-wrap gap-2">
                        <label class="{{ $pill }}">
                            <input type="radio" name="somisy_car" wire:model.live="form.somisy_car" value="yes" class="sr-only"> Yes
                        </label>
                        <label class="{{ $pill }}">
                            <input type="radio" name="somisy_car" wire:model.live="form.somisy_car" value="no" class="sr-only"> No
                        </label>
                        <label class="{{ $pill }}">
                            <input type="radio" name="somisy_car" wire:model.live="form.somisy_car" value="no_vehicle" class="sr-only"> No Vehicle
                        </label>
                    </div>
                    @error('form.somisy_car')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Camp Resident -->
            <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                <label
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">Camp
                    Resident</label>
                <div class="md:col-span-9">
                    <div class="flex flex-wrap gap-2">
                        <label class="{{ $pill }}">
                            <input type="radio" name="resident" wire:model="form.resident" value="yes" class="sr-only"> Yes
                        </label>
                        <label class="{{ $pill }}">
                            <input type="radio" name="resident" wire:model="form.resident" value="no" class="sr-only"> No
                        </label>
                    </div>
                    @error('form.resident')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            @if ($this->showVehicleFields)

                <!-- Drivers (multi-select) -->
                <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 {!! $reqStar !!}">Drivers</label>
                    <div class="md:col-span-9 md:w-1/2">
                        <x-select2-multiple :options="$users" optionLabel="full_name" wire:model="form.driver_ids"
                            placeholder="Select one or more drivers" />
                        @error('form.driver_ids')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Vehicle Type -->
                <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 {!! $reqStar !!}">Vehicle
                        Type</label>

                    <div class="md:col-span-9">

                        <div x-data="{ show: @entangle('form.car_type').live === 'Other' }" x-effect="show = $wire.form.car_type === 'Other'">

                            <div class="flex flex-wrap gap-2">

                                <label class="{{ $pill }}">
                                    <input type="radio" name="car_type" wire:model.live="form.car_type" value="Lv" class="sr-only"> LV
                                </label>

                                <label class="{{ $pill }}">
                                    <input type="radio" name="car_type" wire:model.live="form.car_type" value="Bus" class="sr-only"> Bus
                                </label>

                                <label class="{{ $pill }}">
                                    <input type="radio" name="car_type" wire:model.live="form.car_type" value="Truck" class="sr-only"> Truck
                                </label>

                                <label class="{{ $pill }}">
                                    <input type="radio" name="car_type" wire:model.live="form.car_type" value="Other" class="sr-only"> Other
                                </label>

                            </div>

                            <div x-show="show" x-transition.opacity.duration.200ms class="mt-2">
                                <input type="text" wire:model.defer="form.type_other" placeholder="Enter type"
                                    class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Vehicle Number -->
                <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 {!! $reqStar !!}">Vehicle
                        Number</label>
                    <div class="md:col-span-9" x-data="{}">
                        <div class="flex w-full md:w-1/2 rounded-lg border border-gray-300 bg-gray-50 overflow-hidden focus-within:ring-2 focus-within:ring-[#134169]/20 focus-within:border-[#134169]">
                            <span x-show="$wire.form.car_type === 'Lv'" x-cloak
                                class="inline-flex items-center px-3 bg-gray-100 text-slate-600 text-sm font-semibold border-r border-gray-300 select-none">LV-</span>
                            <input type="text" name="vehicle_number" wire:model="form.car_number"
                                placeholder="Vehicle number"
                                class="flex-1 min-w-0 bg-gray-50 px-4 py-2 outline-none border-0 focus:ring-0">
                        </div>
                        <p class="text-xs text-slate-400 mt-1" x-show="$wire.form.car_type === 'Lv'" x-cloak>Prefix “LV-” is added automatically.</p>
                        @error('form.car_number')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            @else
                <!-- Passengers (multi-select) -->
                <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 {!! $reqStar !!}">Residents</label>
                    <div class="md:col-span-9 md:w-1/2">
                        <x-select2-multiple :options="$users" optionLabel="full_name" wire:model="form.passenger_ids"
                            placeholder="Select one or more residents" />
                        @error('form.passenger_ids')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="flex items-center gap-2 mb-4">
                <label for="default-checkbox" class="text-sm font-medium text-heading select-none">
                    Long term
                </label>

                <input id="default-checkbox" type="checkbox" wire:model.live="date_long"
                    class="w-4 h-4 border border-default-medium rounded bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
            </div>

            <!-- Dates -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">

                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 {!! $reqStar !!}">Date Valid
                    From</label>
                <div class="md:col-span-9">
                    <input type="date" name="date_from" wire:model.live="form.start"
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                    @error('form.start')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Date Until</label>
                <div class="md:col-span-9">
                    <input type="date" name="date_to" wire:model.live="form.end" readonly
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">

                </div>
            </div>


            @if ($date_long)
                {{-- comment  --}}
                <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                    <label
                        class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Justification</label>
                    <div class="md:col-span-9">
                        <input type="text" name="comment" wire:model="form.comment"
                            class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                        @error('form.comment')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Time -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 {!! $reqStar !!}">Departure
                    Time</label>
                <div class="md:col-span-9">
                    <input type="time" name="time_out" wire:model="form.depart_at"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                    @error('form.depart_at')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 {!! $reqStar !!}">Arrival Time</label>
                <div class="md:col-span-9">
                    <input type="time" name="time_in" wire:model="form.arrive_at"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                    @error('form.arrive_at')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Destination(s) -->
            <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                <label
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">
                    Destination(s)
                </label>

                <div class="md:col-span-9" x-data="{ show: @entangle('form.destination').live === 'Other' }"
                    x-effect="show = $wire.form.destination === 'Other'">

                    {{-- radios en pastilles --}}
                    <div class="flex flex-wrap gap-2">

                        <label class="{{ $pill }}">
                            <input type="radio" name="destination" wire:model.live="form.destination" value="Paysan" class="sr-only"> Paysan
                        </label>

                        <label class="{{ $pill }}">
                            <input type="radio" name="destination" wire:model.live="form.destination" value="Taba" class="sr-only"> Taba
                        </label>

                        <label class="{{ $pill }}">
                            <input type="radio" name="destination" wire:model.live="form.destination" value="A21" class="sr-only"> A21
                        </label>

                        <label class="{{ $pill }}">
                            <input type="radio" name="destination" wire:model.live="form.destination" value="Other" class="sr-only"> Other
                        </label>

                    </div>

                    {{-- Other input — même largeur que autres inputs --}}
                    <div x-show="show" x-transition.opacity.duration.200ms class="mt-2">
                        <input type="text" wire:model.defer="form.destination_other"
                            placeholder="Enter destination"
                            class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                    </div>


                    @error('form.destination')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror

                </div>
            </div>



            <!-- Reason -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 {!! $reqStar !!}">Reason for
                    Travel</label>
                <div class="md:col-span-9">
                    <input type="text" name="reason" wire:model="form.reason"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                    @error('form.reason')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-center gap-4">
                <x-form-action cancel="car.index" target="save" />
            </div>
        </div>
    </form>
</div>
