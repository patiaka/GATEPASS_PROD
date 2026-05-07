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

                <!-- Drivers header -->
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Drivers</h2>
                    <button type="button" wire:click="addDriver"
                        class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add driver
                    </button>

                </div>

                @foreach ($form->drivers as $index => $driver)
                    <!-- Driver Name -->
                    <div class="md:grid md:grid-cols-12 md:items-start md:gap-4"
                        wire:key="form.drivers.{{ $index }}.user_id">
                        <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Driver
                            Name</label>
                        <div class="md:col-span-9">
                            <div class="flex flex-col gap-2 md:w-1/2">

                                <x-select2 :options="$users" optionLabel="full_name"
                                    wire:model="form.drivers.{{ $index }}.user_id" label=""
                                    placeholder="Select car driver" />

                                <button type="button" wire:click="removeDriver({{ $index }})"
                                    class="inline-flex items-center justify-center rounded-md bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 px-2 py-1 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            @error("form.drivers.$index.user_id")
                                <small class="text-red-500 text-sm">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endforeach

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
                <!-- Passengers header -->
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Passengers</h2>
                    <button type="button" wire:click="addPassenger"
                        class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Add field
                    </button>
                </div>
                @foreach ($form->passengers as $index => $passenger)
                    <div class="md:grid md:grid-cols-12 md:items-center md:gap-4"
                        wire:key="form.passengers.{{ $index }}.user_id">
                        <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">Resident
                            Name</label>
                        <div class="md:col-span-9">
                            <div class="flex flex-col gap-2 md:w-1/2">

                                <x-select2 :options="$users" optionLabel="full_name"
                                    wire:model="form.passengers.{{ $index }}.user_id" label=""
                                    placeholder="Select passenger" />

                                <button type="button" wire:click="removePassenger({{ $index }})"
                                    class="inline-flex items-center justify-center rounded-md bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 px-2 py-1 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            @error("form.passengers.$index.name")
                                <small class="text-red-500 text-sm">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endforeach
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
        </div>

        <!-- Actions -->
        <div class="pt-6">
            <div class="flex justify-start gap-3">
                <a href="{{ route('car.index') }}"
                    class="px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                    class="px-3 py-1.5 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-[#0e3a61]"
                    wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Submit</span>
                    <span wire:loading wire:target="save" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 size-3.5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
