<div class="w-full max-w-lg">
    <form wire:submit="save">
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center gap-3 px-6 py-5 bg-gradient-to-r from-[#0e3a61] to-[#134169]">
                <span class="flex items-center justify-center w-11 h-11 shrink-0 rounded-full bg-white/15 text-white ring-1 ring-white/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </span>
                <div>
                    <h1 class="text-lg font-bold text-white leading-tight">{{ __('Change Password') }}</h1>
                    <p class="text-xs text-white/70">{{ __('Keep your account secure with a strong password.') }}</p>
                </div>
            </div>

            <div class="p-6 space-y-5">

                {{-- Current password --}}
                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-gray-700 mb-1 after:content-['*'] after:ml-0.5 after:text-red-500">{{ __('Current Password') }}</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" wire:model="current_password" autocomplete="current-password"
                            placeholder="{{ __('Enter your current password') }}"
                            class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 pr-11 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                        <button type="button" @click="show = !show" tabindex="-1" :aria-label="show ? '{{ __('Hide password') }}' : '{{ __('Show password') }}'"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.243 4.243L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>

                {{-- New password --}}
                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-gray-700 mb-1 after:content-['*'] after:ml-0.5 after:text-red-500">{{ __('New Password') }}</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" wire:model="password" autocomplete="new-password"
                            placeholder="{{ __('Enter your new password') }}"
                            class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 pr-11 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                        <button type="button" @click="show = !show" tabindex="-1" :aria-label="show ? '{{ __('Hide password') }}' : '{{ __('Show password') }}'"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.243 4.243L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Confirm new password --}}
                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-gray-700 mb-1 after:content-['*'] after:ml-0.5 after:text-red-500">{{ __('Confirm New Password') }}</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" autocomplete="new-password"
                            placeholder="{{ __('Re-enter your new password') }}"
                            class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 pr-11 focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
                        <button type="button" @click="show = !show" tabindex="-1" :aria-label="show ? '{{ __('Hide password') }}' : '{{ __('Show password') }}'"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.243 4.243L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Requirements hint --}}
                <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                    <p class="text-xs font-semibold text-slate-600 mb-2">{{ __('Password requirements') }}</p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-xs text-slate-500">
                        @foreach ([
                            __('At least 8 characters'),
                            __('Upper and lowercase letters'),
                            __('At least one number'),
                            __('At least one symbol'),
                        ] as $req)
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $req }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                <x-form-action cancel="dashboard" target="save" label="Update password" loadingLabel="Updating…" />
            </div>
        </div>
    </form>
</div>
