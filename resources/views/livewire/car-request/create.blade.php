<div>

    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <h1 class="text-xl font-semibold text-gray-800">Resident & Vehicle Off Site Form</h1>
        <a href="" class="text-sm text-blue-600 hover:underline bg-white border rounded px-3 py-1 shadow-sm">
            ← Back to list
        </a>
    </div>

    <form wire:submit="save">
        <div class="bg-white mt-4 p-6 rounded-md shadow border">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Company -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                    <input type="text" name="form.company" required wire:model="form.company"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.company') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Somisy Vehicle -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Somisy Vehicle</label>
                    <div class="flex gap-4">
                        <label><input type="radio" name="form.somisy_car" wire:model="form.somisy_car" value="yes"
                                class="form-radio"> Yes</label>
                        <label><input type="radio" name="form.somisy_car" wire:model="form.somisy_car" value="no"
                                class="form-radio"> No</label>
                    </div>
                    @error('form.somisy_car') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Camp Resident -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Camp Resident</label>
                    <div class="flex gap-4">
                        <label><input type="radio" wire:model="form.resident" name="form.resident" value="Yes"
                                class="form-radio">
                            Yes</label>
                        <label><input type="radio" wire:model="form.resident" name="form.resident" value="No"
                                class="form-radio">
                            No</label>
                    </div>
                    @error('form.resident') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Expatriate -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expatriate</label>
                    <div class="flex gap-4">
                        <label><input type="radio" name="form.expatriate" wire:model="form.expatriate" value="Yes"
                                class="form-radio"> Yes</label>
                        <label><input type="radio" name="form.expatriate" wire:model="form.expatriate" value="No"
                                class="form-radio"> No</label>
                        <label><input type="radio" name="form.expatriate" wire:model="form.expatriate" value="Escort"
                                class="form-radio"> Escort
                            Level</label>
                    </div>
                    @error('form.expatriate') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>


                @foreach ($form->drivers as $index => $driver)
                <!-- Driver Name -->
                <div wire:key="form.drivers.{{ $index }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name</label>
                    <input type="text" name="driver_name" wire:model="form.drivers.{{ $index }}.name"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error("form.drivers.$index.name")
                    <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" wire:model="form.drivers.{{ $index }}.contact"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error("form.drivers.$index.contact")
                    <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
                <!-- Delete Button -->
                <div class="">
                    <button type="button" wire:click="removeDriver({{ $index }})"
                        class="w-full flex justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                @endforeach
                <button type="button" wire:click="addDriver"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add field
                </button>
                <!-- Drivers Section -->
                <!-- Licence -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Licence</label>
                    <div class="flex gap-4 flex-wrap">
                        @foreach (App\Enum\CarRequestLicenceStatus::cases() as $item)
                        <label><input type="radio" name="licence" wire:model="form.licence" value="{{ $item }}"
                                class="form-radio">{{ $item }}</label>
                        @endforeach

                    </div>
                    @error('form.licence') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Vehicle Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                    <div class="flex gap-4">
                        <label><input type="radio" wire:model="form.car_type" name="vehicle_type" value="Lv"
                                class="form-radio"> LV</label>
                        <label><input type="radio" wire:model="form.car_type" name="vehicle_type" value="Bus"
                                class="form-radio"> Bus</label>
                        <label><input type="radio" wire:model="form.car_type" name="vehicle_type" value="Truck"
                                class="form-radio"> Truck</label>
                    </div>
                    @error('form.car_type') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Vehicle Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Number</label>
                    <input type="text" name="vehicle_number" wire:model="form.car_number"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.car_number') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Route -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                    <input type="text" name="route" wire:model="form.route"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.route') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Dates -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Valid From</label>
                    <input type="date" name="date_from" wire:model="form.start"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.start') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Until</label>
                    <input type="date" name="date_until" wire:model="form.end"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.end') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departure Time</label>
                    <input type="time" name="time_out" wire:model="form.depart_at"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.depart_at') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arrival Time</label>
                    <input type="time" name="time_in" wire:model="form.arrive_at"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.arrive_at') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Destinations -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Destination(s)</label>
                    <input type="text" name="destinations" wire:model="form.destination"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('destination') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Reason -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Travel</label>
                    <input type="text" name="reason" wire:model="form.reason"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.reason') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>

                <!-- Residents -->
                @foreach ($form->passengers as $index => $passenger)
                <div wire:key="form.passengers.{{ $index }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resident Name
                    </label>
                    <input type="text" name="resident_name" wire:model="form.passengers.{{ $index }}.name"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error("form.passengers.$index.name")
                    <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone </label>
                    <input type="text" name="resident_phone" wire:model="form.passengers.{{ $index }}.contact"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error("form.passengers.$index.name")
                    <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Delete Button -->
                <div class="col-span-12 md:col-span-2">
                    <button type="button" wire:click="removePassenger({{ $index }})"
                        class="w-full flex justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                @endforeach
                <!-- Add Passenger Button -->
                <button type="button" wire:click="addPassenger"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add field
                </button>
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-6">

            <!-- Buttons -->
            <div class="flex justify-center gap-4 mt-6">
                <a href="{{ route('car.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#0e3a61] hover:bg-[#0e3a61] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Validate</span>
                    <span wire:loading wire:target="save" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
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