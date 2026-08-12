<div class="mx-auto mt-10">
    <div class="flex items-center justify-between pb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-[#0e3a61] tracking-tight">
            {{ __('Update Resident & Vehicle Off Site Form') }}
        </h1>

        <a wire:navigate href="{{ route('car.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              border border-[#0e3a61]
              bg-white
              text-[#0e3a61] text-sm font-medium
              hover:bg-[#0e3a61] hover:text-white
              transition shadow-sm
              focus:outline-none focus:ring-2 focus:ring-[#0e3a61]/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Back to list') }}
        </a>
    </div>


    @if ($carRequest->isRejected())
        <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
                <div class="text-sm">
                    <p class="font-semibold text-rose-800">{{ __('This request was rejected.') }}</p>
                    @if ($carRequest->rejectionReason())
                        <p class="text-rose-700 mt-0.5"><span class="font-medium">{{ __('Reason:') }}</span> {{ $carRequest->rejectionReason() }}</p>
                    @endif
                    <p class="text-rose-700/80 mt-1">{{ __('Correct it below and click') }} <span class="font-semibold">{{ __('Revise & resubmit') }}</span> {{ __('— it will restart the approval process.') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="save">
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8 space-y-8">

            <!-- Company -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">{{ __('Requestor Company') }}</label>
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
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">{{ __('Somisy Vehicle') }}</label>
                <div class="md:col-span-9">
                    <div class="flex flex-col gap-2 md:w-1/2">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.somisy_car" wire:model.live="form.somisy_car"
                                value="yes" class="form-radio"> <span>{{ __('Yes') }}</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.somisy_car" wire:model.live="form.somisy_car"
                                value="no" class="form-radio"> <span>{{ __('No') }}</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.somisy_car" wire:model.live="form.somisy_car"
                                value="no_vehicle" class="form-radio"> <span>{{ __('No Vehicle') }}</span>
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
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">{{ __('Camp Resident') }}</label>
                <div class="md:col-span-9">
                    <div class="flex flex-col gap-2 md:w-1/2">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.resident" wire:model="form.resident" value="yes"
                                class="form-radio"> <span>{{ __('Yes') }}</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="form.resident" wire:model="form.resident" value="no"
                                class="form-radio"> <span>{{ __('No') }}</span>
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
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Drivers') }}</label>
                    <div class="md:col-span-9 md:w-2/3">
                        <x-select2-multiple :options="$users" optionLabel="full_name" wire:model="form.driver_ids"
                            placeholder="{{ __('Select one or more drivers') }}" />
                        @error('form.driver_ids')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Vehicle Type -->
                <div class="md:grid md:grid-cols-12 md:items-start md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Vehicle Type') }}</label>

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
                                    <input type="radio" wire:model.live="form.car_type" value="Truck"> {{ __('Truck') }}
                                </label>

                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" wire:model.live="form.car_type" value="Other"> {{ __('Other') }}
                                </label>

                            </div>

                            <div x-show="show" x-transition.opacity.duration.200ms class="mt-2">
                                <input type="text" wire:model.defer="form.type_other" placeholder="{{ __('Enter type') }}"
                                    class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Vehicle Number -->
                <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Vehicle Number') }}</label>
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
                    <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Residents') }}</label>
                    <div class="md:col-span-9 md:w-2/3">
                        <x-select2-multiple :options="$users" optionLabel="full_name" wire:model="form.passenger_ids"
                            placeholder="{{ __('Select one or more residents') }}" />
                        @error('form.passenger_ids')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="flex items-center gap-2 mb-4">
                <label for="default-checkbox" class="text-sm font-medium text-heading select-none">{{ __('Long term') }}</label>

                <input id="default-checkbox" type="checkbox" wire:model.live="date_long"
                    class="w-4 h-4 border border-default-medium rounded bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
            </div>

            <!-- Dates -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">

                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Date Valid From') }}</label>
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
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Date Until') }}</label>
                <div class="md:col-span-9">
                    <input type="date" name="date_to" wire:model.live="form.end" readonly
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">

                </div>
            </div>


            @if ($date_long)
                {{-- comment  --}}
                <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                    <label
                        class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Justification') }}</label>
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
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Departure Time') }}</label>
                <div class="md:col-span-9">
                    <input type="time" name="time_out" wire:model="form.depart_at"
                        class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    @error('form.depart_at')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Arrival Time') }}</label>
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
                    class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0 after:content-['*'] after:ml-0.5 after:text-red-500">{{ __('Destination(s)') }}</label>

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
                            <span>{{ __('Other') }}</span>
                        </label>

                    </div>

                    {{-- Other input — même largeur que autres inputs --}}
                    <div x-show="show" x-transition.opacity.duration.200ms class="mt-2">
                        <input type="text" wire:model.defer="form.destination_other"
                            placeholder="{{ __('Enter destination') }}"
                            class="w-full md:w-1/2 border border-gray-300 bg-gray-50 rounded-lg px-4 py-2">
                    </div>


                    @error('form.destination')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror

                </div>
            </div>



            <!-- Reason -->
            <div class="md:grid md:grid-cols-12 md:items-center md:gap-4">
                <label class="md:col-span-2 block text-sm font-medium text-gray-700 mb-1 md:mb-0">{{ __('Reason for Travel') }}</label>
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
        <div class="flex justify-start gap-3 mt-6">
            <x-form-action cancel="car.index" target="save"
                :label="$carRequest->isRejected() ? 'Revise & resubmit' : 'Save changes'"
                :loadingLabel="$carRequest->isRejected() ? 'Resubmitting…' : 'Saving…'" />
        </div>
    </form>
</div>
