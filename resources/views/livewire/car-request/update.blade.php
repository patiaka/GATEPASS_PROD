<div>
    <div class="card">
        <h2 class="p-4 text-center">Form of update car request</h2>
        <div class="card-body">
            <form wire:submit="save" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <!-- Somisy Car -->
                        <div class="mb-3">
                            <label class="form-label">Somisy Car</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model.defer='somisy_car'
                                    name="somisy_car" id="somisy_car_yes" value="Yes">
                                <label class="form-check-label" for="somisy_car_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="somisy_car"
                                    wire:model.defer='somisy_car' id="somisy_car_no" value="No">
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
                                <option value="{{ $row }}">{{ $row }}
                                </option>
                                @endforeach
                            </x-select>
                            @error('licence') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Destination -->
                        <x-input type="text" label="Destination" wire:model.defer="destination" />
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
                        <x-input type="text" wire:model.defer="car_number" place=" Vehicle number" />
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
    </div>
</div>
<script>
    $(".select2").each(function () {
            var current = $(this);
            current.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Selectionner",
                dropdownParent: current.parent(),
            });
            // Get the Livewire property name from the wire:model attribute
            var propertyName = current.attr('wire:model.live');
            // Listen for change event and update Livewire property
            current.on('change', function (e) {
                @this.set(propertyName, $(this).val());
            });
        });
</script>
