<div>
    <div class="max-w-2xl">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">

            <!-- Header -->
            <div class="mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-[#134169]">
                    New User
                </h2>
                <p class="text-sm text-gray-500">
                    Create a new user account
                </p>
            </div>

            <x-form route='save' url="{{ route('user.index') }}">

                <div class="space-y-5">

                    <!-- Name -->
                    <div>
                        <x-input type="text" name="name" place="Full name" label="Name" wire:model='form.name' class="w-full" />
                        @error('form.name')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input type="email" name="email" place="Email address" label="Email" wire:model='form.email'
                            class="w-full" />
                        @error('form.email')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <x-input type="text" name="poste" place="Position" label="Position" wire:model='form.poste'
                            class="w-full" />
                        @error('form.poste')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Contact -->
                    <div>
                        <x-input type="text" name="contact" place="Phone / Contact" label="Contact" wire:model='form.contact'
                            class="w-full" />
                        @error('form.contact')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Badge -->
                    <div>
                        <x-input type="text" name="badge_number" place="Badge number" label="Badge Number" wire:model='form.badge_number'
                            class="w-full" />
                        @error('form.badge_number')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <x-select label="Department" name="department" wire:model='form.department_id' class="w-full">
                            @foreach ($departments as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </x-select>
                        @error('form.department_id')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Role
                        </label>

                        <div class="space-y-2">
                            @foreach (App\Enum\RoleEnum::cases() as $row)
                                <label
                                    class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-gray-50
                                              hover:border-[#134169] hover:bg-blue-50 cursor-pointer transition">

                                    <input type="radio" wire:model="form.role" name="form.role"
                                        value="{{ $row }}" class="text-[#134169] focus:ring-[#134169]">

                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $row }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @error('form.role')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

            </x-form>

        </div>
    </div>
</div>
