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
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8 space-y-8">

            <!-- Company -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">Requestor
                    Company</label>
                <div class="md:col-span-9">
                    <input type="text" name="form.company" required wire:model="form.company"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 ">
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
                    <div class="flex flex-col gap-2 md:w-1/2">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.somisy_car" wire:model.live="form.somisy_car"
                                value="yes" class="form-radio"> <span>Yes</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.somisy_car" wire:model.live="form.somisy_car"
                                value="no" class="form-radio"> <span>No</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.somisy_car" wire:model.live="form.somisy_car"
                                value="no_vehicle" class="form-radio"> <span>No Vehicle</span>
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
                    <div class="flex flex-col gap-2 md:w-1/2">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.resident" wire:model="form.resident" value="yes"
                                class="form-radio"> <span>Yes</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.resident" wire:model="form.resident" value="no"
                                class="form-radio"> <span>No</span>
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
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Drivers</label>
                    <div class="md:col-span-9 md:w-2/3">
                        <x-select2-multiple :options="$users" optionLabel="full_name" wire:model="form.driver_ids"
                            placeholder="Select one or more drivers" />
                        @error('form.driver_ids')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Vehicle Type -->
                <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Vehicle
                        Type</label>

                    <div class="md:col-span-9">

                        <div x-data="{ show: @entangle('form.car_type').live === 'Other' }" x-effect="show = $wire.form.car_type === 'Other'">

                            <div class="flex flex-col gap-2 md:w-1/2">

                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" wire:model.live="form.car_type" value="Lv"> LV
                                </label>

                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" wire:model.live="form.car_type" value="Bus"> Bus
                                </label>

                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" wire:model.live="form.car_type" value="Truck"> Truck
                                </label>

                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" wire:model.live="form.car_type" value="Other"> Other
                                </label>

                            </div>

                            <div x-show="show" x-transition.opacity.duration.200ms class="mt-2">
                                <input type="text" wire:model.defer="form.type_other" placeholder="Enter type"
                                    class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Vehicle Number -->
                <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Vehicle
                        Number</label>
                    <div class="md:col-span-9">
                        <input type="text" name="vehicle_number" wire:model="form.car_number"
                            class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                        @error('form.car_number')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            @else
                <!-- Passengers (multi-select) -->
                <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Residents</label>
                    <div class="md:col-span-9 md:w-2/3">
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

                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Date Valid
                    From</label>
                <div class="md:col-span-9">
                    <input type="date" name="date_from" wire:model.live="form.start"
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.start')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Date Until</label>
                <div class="md:col-span-9">
                    <input type="date" name="date_to" wire:model.live="form.end" readonly
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">

                </div>
            </div>


            @if ($date_long)
                {{-- comment  --}}
                <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                    <label
                        class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Justification</label>
                    <div class="md:col-span-9">
                        <input type="text" name="comment" wire:model="form.comment"
                            class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                        @error('form.comment')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Time -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Departure
                    Time</label>
                <div class="md:col-span-9">
                    <input type="time" name="time_out" wire:model="form.depart_at"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.depart_at')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Arrival Time</label>
                <div class="md:col-span-9">
                    <input type="time" name="time_in" wire:model="form.arrive_at"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
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

                    {{-- radios — même largeur que Vehicle Type --}}
                    <div class="flex flex-col gap-2 md:w-1/2">

                        <label class="inline-flex items-center gap-2">
                            <input type="radio" wire:model.live="form.destination" value="Paysan"
                                class="form-radio">
                            <span>Paysan</span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input type="radio" wire:model.live="form.destination" value="Taba"
                                class="form-radio">
                            <span>Taba</span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input type="radio" wire:model.live="form.destination" value="A21"
                                class="form-radio">
                            <span>A21</span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input type="radio" wire:model.live="form.destination" value="Other"
                                class="form-radio">
                            <span>Other</span>
                        </label>

                    </div>

                    {{-- Other input — même largeur que autres inputs --}}
                    <div x-show="show" x-transition.opacity.duration.200ms class="mt-2">
                        <input type="text" wire:model.defer="form.destination_other"
                            placeholder="Enter destination"
                            class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    </div>


                    @error('form.destination')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror

                </div>
            </div>



            <!-- Reason -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Reason for
                    Travel</label>
                <div class="md:col-span-9">
                    <input type="text" name="reason" wire:model="form.reason"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
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
