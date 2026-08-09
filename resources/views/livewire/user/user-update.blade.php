<div>
    <div class="max-w-2xl">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">

            <!-- Header -->
            <div class="mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-[#134169]">
                    Update User
                </h2>
                <p class="text-sm text-gray-500">
                    Update user account information
                </p>
            </div>

            <x-form action="save" type="update" route="user.index">

                <div class="space-y-5">

                    <!-- Name -->
                    <div>
                        <x-input type="text" name="name" place="Full name" label="Full Name" wire:model="form.name" class="w-full" />
                        @error('form.name')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input type="email" name="email" place="Email address" label="Email Address" wire:model="form.email"
                            class="w-full" />
                        @error('form.email')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <x-input type="text" name="poste" place="Position" label="Position" wire:model="form.poste"
                            class="w-full" />
                        @error('form.poste')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Contact -->
                    <div>
                        <x-input type="text" name="contact" place="Phone / Contact" label="Phone / Contact" wire:model="form.contact"
                            class="w-full" />
                        @error('form.contact')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Badge -->
                    <div>
                        <x-input type="text" name="badge_number" place="Badge number" label="Badge Number" wire:model="form.badge_number"
                            class="w-full" />
                        @error('form.badge_number')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <x-select label="Department" name="department" wire:model="form.department_id" class="w-full">
                            @foreach ($departments as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </x-select>
                        @error('form.department_id')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Roles (multi) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Roles <span class="text-xs font-normal text-gray-400">(un ou plusieurs)</span>
                        </label>

                        <div class="space-y-2">
                            @foreach (App\Enum\RoleEnum::cases() as $row)
                                <label
                                    class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-gray-50
                                           hover:border-[#134169] hover:bg-blue-50 cursor-pointer transition">

                                    <input type="checkbox" wire:model="form.roles"
                                        value="{{ $row->value }}" class="rounded text-[#134169] focus:ring-[#134169]">

                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $row->value }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @error('form.roles')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Reset password (optionnel) --}}
                    <div class="border-t pt-5">
                        <p class="text-sm font-semibold text-gray-700">Reset password</p>
                        <p class="text-xs text-gray-400 mb-3">Leave empty to keep the current password. Min. 8 characters.</p>

                        <div class="space-y-4">
                            <div>
                                <x-input type="password" name="password" place="New password" label="New password"
                                    :required="false" wire:model="form.password" class="w-full" />
                                @error('form.password')
                                    <small class="text-red-500 text-xs">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <x-input type="password" name="password_confirmation" place="Confirm new password"
                                    label="Confirm new password" :required="false" wire:model="form.password_confirmation"
                                    class="w-full" />
                            </div>
                        </div>
                    </div>

                </div>

            </x-form>

        </div>
    </div>
</div>
