<div>
    <div class="card bg-white shadow-sm p-5 mx-auto w-full max-w-sm">
        <div class="card-header">
            <h2 class="text-2xl font-bold text-[#134169] tracking-tight mb-4 text-center">Change Password</h2>
        </div>
        <x-form action='save' route='dashboard'>
            <div class="mb-3 form-password-toggle">
                <x-input type="password" name="current_password" wire:model="current_password" place="your current password" label="Current Password" class="w-full"/>
                {{-- <label class="form-label" for="password">Current password</label>
                <div class="input-group input-group-merge">
                    <input type="password" id="password" wire:model="current_password"
                        class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500"
                        name="password" placeholder="Enter your current password" aria-describedby="password"
                        required />

                </div> --}}
                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
            </div>

            <div class="mb-3 form-password-toggle">
                <x-input type="password" name="password" wire:model="password" place="New Password" label="New Password" class="w-full"/>
                {{-- <label class="form-label" for="password">New password</label>
                <div class="input-group input-group-merge">
                    <input type="password" id="password" wire:model="password"
                        class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500"
                        name="password" placeholder="Enter new your password" aria-describedby="password" required />

                </div> --}}
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mb-3 form-password-toggle">
                <x-input type="password" name="password_confirmation" wire:model="password_confirmation" place="New Password" label="Confirm New Password" class="w-full"/>
                {{-- <label class="form-label" for="password_confirmation">Confirm new password</label>
                <div class="input-group input-group-merge">
                    <input type="password" id="password_confirmation" wire:model="password_confirmation"
                        class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500"
                        name="password_confirmation" placeholder="Confirm new password" aria-describedby="password"
                        required />
                </div> --}}
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </x-form>
    </div>
</div>