<div>
    {{-- <div class="card">
        <h2 class="p-4 text-center">Form of new car request</h2>
        <div class="card-body">
            <form wire:submit="save" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <!-- Somisy Car -->
                        <div class="mb-3">
                            <label class="form-label">Somisy Car</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model.defer="somisy_car"
                                    name="somisy_car" id="somisy_car_yes" value="Yes">
                                <label class="form-check-label" for="somisy_car_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model.defer="somisy_car"
                                    name="somisy_car" id="somisy_car_no" value="No">
                                <label class="form-check-label" for="somisy_car_no">No</label>
                            </div>
                            @error('somisy_car') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Resident -->
                        <div class="mb-3">
                            <label class="form-label">Resident</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model.defer="resident" name="resident"
                                    id="resident_yes" value="Yes">
                                <label class="form-check-label" for="resident_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model.defer="resident" name="resident"
                                    id="resident_no" value="No">
                                <label class="form-check-label" for="resident_no">No</label>
                            </div>
                            @error('resident') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Expatriate -->
                        <div class="mb-3">
                            <label class="form-label">Expatriate</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="expatriate_yes" name="expatriate"
                                    wire:model.defer='expatriate' value="Yes">
                                <label class="form-check-label" for="expatriate_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="expatriate_no" name="expatriate"
                                    wire:model.defer='expatriate' value="No">
                                <label class="form-check-label" for="expatriate_no">No</label>
                            </div>
                            @error('expatriate') <small class="text-danger">{{ $message }}</small> @enderror

                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Liste des drivers -->
                        <div class="mb-4">
                            <h5 class="mb-3">Car Driver infos</h5>
                            @foreach ($drivers as $index => $driver)
                            <div class="row mb-2" wire:key="drivers.{{ $index }}">
                                <!-- Désignation -->
                                <div class="col-md-6">
                                    <label for="designation">Name</label>
                                    <input type="text" wire:model="drivers.{{ $index }}.name" placeholder="Name"
                                        class="form-control">
                                    @error("drivers.$index.name")
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-4">
                                    <label for="phone">Phone</label>
                                    <input type="text" wire:model="drivers.{{ $index }}.contact" placeholder="contact"
                                        class="form-control" min="1">
                                    @error("drivers.$index.contact")
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Bouton supprimer -->
                                <div class="col-md-2">
                                    <button type="button" wire:click="remove('driver', {{ $index }})"
                                        class="btn btn-danger mt-3">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Bouton ajouter un driver -->
                        <div class="mb-4">
                            <button type="button" wire:click="add('driver')" class="btn btn-success">
                                <i class="bx bx-plus"></i>
                                Add field
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Liste des passengers -->
                        <div class="mb-4">
                            <h5 class="mb-3"> RESIDENT PASSENGER DETAILS</h5>
                            @foreach ($passengers as $index => $passenger)
                            <div class="row mb-2" wire:key="passengers.{{ $index }}">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <label for="designation">Name</label>
                                    <input type="text" wire:model="passengers.{{ $index }}.name" placeholder="Name"
                                        class="form-control">
                                    @error("passengers.$index.name")
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-4">
                                    <label for="phone">Phone</label>
                                    <input type="text" wire:model="passengers.{{ $index }}.contact"
                                        placeholder="contact" class="form-control" min="1">
                                    @error("passengers.$index.contact")
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Bouton supprimer -->
                                <div class="col-md-2">
                                    <button type="button" wire:click="remove('passenger', {{ $index }})"
                                        class="btn btn-danger mt-3">
                                        <i class="bx bx-trash"></i>

                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Bouton ajouter un driver -->
                        <div class="mb-4">
                            <button type="button" wire:click="add('passenger')" class="btn btn-success">
                                <i class="bx bx-plus"></i>
                                Add field
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div wire:ignore>
                            <!-- Licence -->
                            <x-select wire:model.live="licence" label="Licence">
                                @foreach (App\Enum\CarRequestLicenceStatus::cases() as $row)
                                <option value="{{ $row }}">{{ $row }}</option>
                                @endforeach
                            </x-select>
                            @error('licence') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Destination -->
                        <x-input type="text" label="Destination" wire:model.defer="destination" place="destination" />
                        @error('destination') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <div wire:ignore>
                            <!-- Car Type -->
                            <x-select wire:model.live="car_type" label="Vehicle Type">
                                <option value="">-- Select Vehicle Type --</option>
                                <option value="Lv">Lv</option>
                                <option value="Bus">Bus</option>
                                <option value="Truck">Truck</option>
                            </x-select>
                            @error('car_type') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Car Number -->
                        <x-input type="text" wire:model.defer="car_number" label="Vehicle Number"
                            place=" Vehicle number" />
                        @error('car_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <!-- Start Date -->
                        <x-flatpickr wire:model.defer="start" id="start" label="Start Date" />
                        @error('start') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <!-- End Date -->
                        <x-flatpickr wire:model.defer="end" id="end" label="End Date" />
                        @error('end') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <!-- Departure Time -->
                        <x-flatpickr-time wire:model.defer="depart_at" id="depart_at" label="Departure Time" />
                        @error('depart_at') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <!-- Arrival Time -->
                        <x-flatpickr-time wire:model.defer="arrive_at" label="Arrival Time" id="arrive_at" />
                        @error('arrive_at') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Justification -->
                    <x-textarea wire:model.defer="justification" label="Justification" place="Provide justification" />
                    @error('justification') <small class="text-danger">{{ $message }}</small> @enderror

                </div>

                <!-- Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('car.index') }}" class="btn btn-outline-danger">Cancel</a>
                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">Validate</span>
                        <span wire:loading wire:target="save">
                            <i class="bx bx-loader-alt fa-spin"></i> Traitement...
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div> --}}
    <div class="bg-white rounded-lg shadow-md">
        <h2 class="p-4 text-center text-xl font-semibold">Form of new car request</h2>
        <div class="p-6">
            <form wire:submit="save" method="post" class="space-y-6">
                @csrf
                <div class="grid grid-cols-12 gap-6">
                    <!-- Radio Button Groups -->
                    <div class="col-span-12 md:col-span-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Somisy Car</label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model.defer="somisy_car" name="somisy_car" value="Yes"
                                        class="size-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model.defer="somisy_car" name="somisy_car" value="No"
                                        class="size-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">No</span>
                                </label>
                            </div>
                            @error('somisy_car') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Resident</label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model.defer="resident" name="resident" value="Yes"
                                        class="size-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model.defer="resident" name="resident" value="No"
                                        class="size-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">No</span>
                                </label>
                            </div>
                            @error('resident') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Expatriate</label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model.defer="expatriate" name="expatriate" value="Yes"
                                        class="size-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model.defer="expatriate" name="expatriate" value="No"
                                        class="size-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">No</span>
                                </label>
                            </div>
                            @error('expatriate') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <!-- Drivers Section -->
                    <div class="col-span-12 md:col-span-6">
                        <div class="space-y-4">
                            <h5 class="text-lg font-medium">Car Driver infos</h5>
                            @foreach ($drivers as $index => $driver)
                            <div class="grid grid-cols-12 gap-4 items-end" wire:key="drivers.{{ $index }}">
                                <!-- Name -->
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input type="text" wire:model="drivers.{{ $index }}.name" placeholder="Name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error("drivers.$index.name")
                                    <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Contact -->
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                    <input type="text" wire:model="drivers.{{ $index }}.contact" placeholder="Contact"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error("drivers.$index.contact")
                                    <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Delete Button -->
                                <div class="col-span-12 md:col-span-2">
                                    <button type="button" wire:click="remove('driver', {{ $index }})"
                                        class="w-full flex justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach

                            <!-- Add Driver Button -->
                            <button type="button" wire:click="add('driver')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add field
                            </button>
                        </div>
                    </div>

                    <!-- Passengers Section -->
                    <div class="col-span-12 md:col-span-6">
                        <div class="space-y-4">
                            <h5 class="text-lg font-medium">RESIDENT PASSENGER DETAILS</h5>
                            @foreach ($passengers as $index => $passenger)
                            <div class="grid grid-cols-12 gap-4 items-end" wire:key="passengers.{{ $index }}">
                                <!-- Name -->
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input type="text" wire:model="passengers.{{ $index }}.name" placeholder="Name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error("passengers.$index.name")
                                    <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Contact -->
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                    <input type="text" wire:model="passengers.{{ $index }}.contact"
                                        placeholder="Contact"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error("passengers.$index.contact")
                                    <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Delete Button -->
                                <div class="col-span-12 md:col-span-2">
                                    <button type="button" wire:click="remove('passenger', {{ $index }})"
                                        class="w-full flex justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach

                            <!-- Add Passenger Button -->
                            <button type="button" wire:click="add('passenger')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add field
                            </button>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="col-span-12 md:col-span-6" wire:ignore>
                        <x-select wire:model.live="licence" label="Licence">
                            @foreach (App\Enum\CarRequestLicenceStatus::cases() as $row)
                            <option value="{{ $row }}">{{ $row }}</option>
                            @endforeach
                        </x-select>
                        @error('licence') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <x-input type="text" label="Destination" wire:model.defer="destination"
                            placeholder="Destination" />
                        @error('destination') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-span-12 md:col-span-6" wire:ignore>
                        <x-select wire:model.live="car_type" label="Vehicle Type">
                            <option value="">-- Select Vehicle Type --</option>
                            <option value="Lv">Lv</option>
                            <option value="Bus">Bus</option>
                            <option value="Truck">Truck</option>
                        </x-select>
                        @error('car_type') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <x-input type="text" wire:model.defer="car_number" label="Vehicle Number"
                            placeholder="Vehicle number" />
                        @error('car_number') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <x-input wire:model.defer="start" id="start" label="Start Date" type="date" />
                        @error('start') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <x-input wire:model.defer="end" id="end" label="End Date" type="date" />
                        @error('end') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <x-input wire:model.defer="depart_at" id="depart_at" label="Departure Time" type="time" />
                        @error('depart_at') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <x-input wire:model.defer="arrive_at" label="Arrival Time" id="arrive_at" type="time" />
                        @error('arrive_at') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-span-12">
                        <x-textarea wire:model.defer="justification" label="Justification"
                            placeholder="Provide justification" />
                        @error('justification') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-center gap-4 mt-6">
                    <a href="{{ route('car.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">Validate</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <h1 class="text-xl font-semibold text-gray-800">Resident & Vehicle Off Site Form</h1>
        <a href="" class="text-sm text-blue-600 hover:underline bg-white border rounded px-3 py-1 shadow-sm">
            ← Back to list
        </a>
    </div>

    <div class="bg-white mt-4 p-6 rounded-md shadow border">
        <form action="" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Company -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                    <input type="text" name="company" required
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Somisy Vehicle -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Somisy Vehicle</label>
                    <div class="flex gap-4">
                        <label><input type="radio" name="somisy_car" value="yes" class="form-radio"> Yes</label>
                        <label><input type="radio" name="somisy_car" value="no" class="form-radio"> No</label>
                    </div>
                </div>

                <!-- Camp Resident -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Camp Resident</label>
                    <div class="flex gap-4">
                        <label><input type="radio" name="resident" value="yes" class="form-radio"> Yes</label>
                        <label><input type="radio" name="resident" value="no" class="form-radio"> No</label>
                    </div>
                </div>

                <!-- Expatriate -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expatriate</label>
                    <div class="flex gap-4">
                        <label><input type="radio" name="expatriate" value="yes" class="form-radio"> Yes</label>
                        <label><input type="radio" name="expatriate" value="no" class="form-radio"> No</label>
                        <label><input type="radio" name="expatriate" value="escort" class="form-radio"> Escort
                            Level</label>
                    </div>
                </div>

                <!-- Driver Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name</label>
                    <input type="text" name="driver_name"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Licence -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Licence</label>
                    <div class="flex gap-4 flex-wrap">
                        <label><input type="radio" name="licence" value="mali_dl" class="form-radio"> Mali DL</label>
                        <label><input type="radio" name="licence" value="foreign_dl" class="form-radio"> Foreign
                            DL</label>
                        <label><input type="radio" name="licence" value="international_permit" class="form-radio">
                            International Permit</label>
                    </div>
                </div>

                <!-- Vehicle Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                    <div class="flex gap-4">
                        <label><input type="radio" name="vehicle_type" value="LV" class="form-radio"> LV</label>
                        <label><input type="radio" name="vehicle_type" value="Bus" class="form-radio"> Bus</label>
                        <label><input type="radio" name="vehicle_type" value="Truck" class="form-radio"> Truck</label>
                    </div>
                </div>

                <!-- Vehicle Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Number</label>
                    <input type="text" name="vehicle_number"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Route -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                    <input type="text" name="route"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Dates -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Valid From</label>
                    <input type="date" name="date_from"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Until</label>
                    <input type="date" name="date_until"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departure Time</label>
                    <input type="time" name="time_out"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arrival Time</label>
                    <input type="time" name="time_in"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Destinations -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Destination(s)</label>
                    <input type="text" name="destinations"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Reason -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Travel</label>
                    <input type="text" name="reason"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                </div>

                <!-- Residents -->
                @for ($i = 1; $i <= 2; $i++) <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resident Name
                        {{ $i }}</label>
                    <input type="text" name="resident_name_{{ $i }}"
                        class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone {{ $i }}</label>
                <input type="text" name="resident_phone_{{ $i }}"
                    class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
            </div>
            @endfor
    </div>

    <!-- Submit -->
    <div class="pt-6">
        <button type="submit"
            class="flex items-center space-x-2 bg-[#0e3a61] text-white px-4 py-2 rounded shadow hover:bg-blue-900">
            <span>💾</span>
            <span>Submit</span>
        </button>
    </div>
    </form>
</div>
</div>