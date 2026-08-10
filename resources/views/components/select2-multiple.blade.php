@props([
    'options' => [],
    'placeholder' => 'Select options',
    'optionValue' => 'id',
    'optionLabel' => 'name',
])

@php $wireModel = $attributes->wire('model')->value(); @endphp

{{--
    Multi-select fiable : le binding des données passe par des cases à cocher
    natives wire:model (Livewire collecte les valeurs cochées dans un tableau).
    Alpine ne gère QUE l'affichage : ouverture, recherche client, et chips.
--}}
<div x-data="{
        open: false,
        search: '',
        // Initialisé depuis le modèle Livewire (et non le DOM) pour éviter la
        // course : à l'édition, les cases ne sont cochées qu'après l'init Alpine.
        selected: ($wire.get('{{ $wireModel }}') ?? []).map(String),
        syncFromDom() {
            this.selected = [...$root.querySelectorAll('input[type=checkbox]:checked')].map(c => c.value);
        },
    }"
    @click.outside="open = false"
    class="relative">

    {{-- Contrôle : chips des sélections + ouverture --}}
    <div @click="open = !open"
        class="flex flex-wrap items-center gap-1.5 min-h-[42px] w-full px-2 py-1.5 bg-white border rounded-lg cursor-pointer transition"
        :class="open ? 'border-[#134169] ring-2 ring-[#134169]/20' : 'border-gray-300 hover:border-gray-400'">

        <template x-if="selected.length === 0">
            <span class="text-gray-400 px-1 text-sm">{{ $placeholder }}</span>
        </template>

        @foreach ($options as $opt)
            <span x-show="selected.map(String).includes(@js((string) $opt[$optionValue]))"
                class="inline-flex items-center gap-1 rounded-md bg-[#134169]/10 text-[#134169] text-xs font-medium pl-2 pr-1 py-1">
                {{ $opt[$optionLabel] }}
                <button type="button"
                    @click.stop="
                        let cb = $root.querySelector('input[value=\'{{ $opt[$optionValue] }}\']');
                        if (cb) { cb.checked = false; cb.dispatchEvent(new Event('change', { bubbles: true })); syncFromDom(); }
                    "
                    class="rounded hover:bg-[#134169]/20 p-0.5" aria-label="Remove">
                    <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 8.586 4.707 3.293 3.293 4.707 8.586 10l-5.293 5.293 1.414 1.414L10 11.414l5.293 5.293 1.414-1.414L11.414 10l5.293-5.293-1.414-1.414L10 8.586z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </span>
        @endforeach

        <svg class="ml-auto h-5 w-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"
            viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </div>

    {{-- Dropdown : recherche + cases à cocher natives --}}
    <div x-show="open" x-cloak
        class="absolute z-20 w-full mt-1 bg-white border border-gray-200 shadow-lg rounded-md">

        <div class="p-2 border-b">
            <input x-model="search" type="text" placeholder="Rechercher..."
                class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
        </div>

        <div class="max-h-60 overflow-y-auto py-1">
            @forelse ($options as $opt)
                <label
                    x-show="@js(mb_strtolower((string) $opt[$optionLabel])).includes(search.toLowerCase())"
                    class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer text-gray-700 hover:bg-slate-100 has-[:checked]:bg-[#134169]/5 has-[:checked]:text-[#134169] has-[:checked]:font-medium transition">
                    <input type="checkbox" wire:model="{{ $wireModel }}" value="{{ $opt[$optionValue] }}"
                        @change="syncFromDom()"
                        class="rounded text-[#134169] focus:ring-[#134169]">
                    <span>{{ $opt[$optionLabel] }}</span>
                </label>
            @empty
                <div class="px-3 py-2 text-sm text-gray-500">Aucune option</div>
            @endforelse
        </div>
    </div>
</div>
