<div class="p-4 md:p-6">

    {{-- Header --}}
    <div class="border-b pb-4 mb-6">
        <h1 class="text-2xl font-bold text-[#134169]">{{ __('Settings') }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ __('Application parameters & business rules') }}</p>
    </div>

    <div class="max-w-2xl">
        <form wire:submit.prevent="save"
            class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-6">

            {{-- Section : validité --}}
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#134169]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                </svg>
                <h2 class="font-semibold text-sm text-[#134169]">{{ __('Request validity') }}</h2>
            </div>

            <div>
                <label for="material_validity_days" class="block text-sm font-medium text-slate-700 mb-1">
                    {{ __('Material request validity') }} <span class="text-slate-400 font-normal">{{ __('(days)') }}</span>
                </label>
                <div class="flex items-center gap-3">
                    <input id="material_validity_days" type="number" min="1" max="365"
                        wire:model="material_validity_days"
                        class="w-32 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                    <span class="text-sm text-slate-500">{{ __('days after GM approval') }}</span>
                </div>
                <p class="text-xs text-slate-400 mt-1.5">
                    {{ __('A material request stays valid for this many days, counted from the date it is approved by the General Manager.') }}
                </p>
                @error('material_validity_days')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Véhicule : deux durées (standard / longue) --}}
            <div class="pt-5 border-t border-gray-100">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-[#134169]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13l2-5a2 2 0 0 1 1.9-1.4h10.2A2 2 0 0 1 19 8l2 5m-18 0v4a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-4m-18 0h18M6.5 16.5h.01M17.5 16.5h.01" />
                    </svg>
                    <h2 class="font-semibold text-sm text-[#134169]">{{ __('Vehicle validity') }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="vehicle_validity_days" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('Standard') }} <span class="text-slate-400 font-normal">{{ __('(days)') }}</span>
                        </label>
                        <input id="vehicle_validity_days" type="number" min="1" max="365"
                            wire:model="vehicle_validity_days"
                            class="w-32 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                        @error('vehicle_validity_days')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vehicle_validity_days_long" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('Long duration') }} <span class="text-slate-400 font-normal">{{ __('(days)') }}</span>
                        </label>
                        <input id="vehicle_validity_days_long" type="number" min="1" max="365"
                            wire:model="vehicle_validity_days_long"
                            class="w-32 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                        @error('vehicle_validity_days_long')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-2">
                    {{ __("A vehicle request's validity end date is computed from its start date. « Long duration » applies when the requester ticks the long-duration option.") }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-[#134169] text-white text-sm font-medium hover:bg-[#0e3a61] shadow-sm transition disabled:opacity-60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span wire:loading.remove wire:target="save">{{ __('Save changes') }}</span>
                    <span wire:loading wire:target="save">{{ __('Saving…') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
