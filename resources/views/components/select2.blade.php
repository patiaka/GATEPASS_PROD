@props([
'options' => [],
'placeholder' => 'Select an option',
'name' => '',
'label' => null,
'labelClass' => '',
'required' => false,
'sm' => false,
'optionValue' => 'id',
'optionLabel' => 'reference',
'asyncUrl' => null
])

<div x-data="select2({
        options: @js($options),
        placeholder: '{{ $placeholder }}',
        value: @entangle($attributes->wire('model')),
        wireModel: '{{ $attributes->wire('model')->value() }}',
        optionValue: '{{ $optionValue }}',
        optionLabel: '{{ $optionLabel }}',
        asyncUrl: '{{ $asyncUrl }}'
    })" wire:ignore class="space-y-3" @click.stop x-init="init()">

    @if($label)
    <label for="{{ $name }}" class="label-text uppercase text-xs font-bold text-gray-700 {{ $labelClass }}">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <div class="relative">
        <!-- Select Button -->
        <div @click="toggleDropdown()"
            @class([ 'flex items-center justify-between w-full px-4 py-2 mb-3 text-left bg-white border cursor-pointer input'
            , 'input-sm'=> $sm,
            'border-error' => $errors->has($attributes->wire('model')->value())
            ])
            :class="{
            'border-blue-500 ring-2 ring-blue-100': isOpen,
            'border-gray-300 hover:border-gray-400': !isOpen
            }">

            <div class="flex items-center flex-1 min-h-[1.5rem]">
                <template x-if="!selectedOption">
                    <span class="text-gray-500" x-text="placeholderText"></span>
                </template>
                <template x-if="selectedOption">
                    <span class="truncate" x-text="selectedOption[optionLabel]"></span>
                </template>
            </div>

            <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': isOpen }"
                viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <!-- Dropdown Menu -->
        <div x-show="isOpen" x-transition @click.outside="isOpen = false"
            class="absolute z-10 w-full mt-1 bg-white border border-gray-200 shadow-lg rounded-md">
            <!-- Search Input -->
            <div class="p-2 border-b">
                <input x-ref="searchInput" x-model="searchTerm" @keydown.enter.prevent="selectHighlighted()" type="text"
                    class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Rechercher...">
            </div>

            <!-- Options List -->
            <div class="max-h-60 overflow-y-auto py-1">
                <template x-if="filteredOptions.length === 0">
                    <div class="px-4 py-2 text-sm text-gray-500">Aucun résultat</div>
                </template>

                <template x-for="(option, index) in filteredOptions" :key="option[optionValue]">
                    <div @click="selectOption(option)" @mouseenter="highlightedIndex = index"
                        class="px-4 py-2 text-sm cursor-pointer flex items-center justify-between rounded-md transition"
                        :class="{
                             'bg-blue-100 text-blue-800 font-medium': isSelected(option[optionValue]),
                             'bg-base-200': highlightedIndex === index && !isSelected(option[optionValue])
                         }">
                        <span x-text="option[optionLabel]"></span>
                        <template x-if="isSelected(option[optionValue])">
                            <svg class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        @error($attributes->wire('model')->value())
        <span class="text-error text-sm">{{ $message }}</span>
        @enderror
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
    Alpine.data('select2', (config) => ({
        options: config.options || [],
        placeholderText: config.placeholder || 'Select an option',
        value: config.value || null,
        wireModel: config.wireModel || '',
        optionValue: config.optionValue || 'id',
        optionLabel: config.optionLabel || 'nom',
        asyncUrl: config.asyncUrl || null,

        // State
        isOpen: false,
        searchTerm: '',
        selectedOption: null,
        highlightedIndex: null,

        init() {
            // Initialize with default value
            this.syncFromLivewire(this.value);

            // Watch for external changes
            this.$watch('value', (value) => {
                this.syncFromLivewire(value);
            });
        },

        get filteredOptions() {
            if (!this.searchTerm) return this.options;

            return this.options.filter(option => {
                const label = option[this.optionLabel]?.toString().toLowerCase() || '';
                return label.includes(this.searchTerm.toLowerCase());
            });
        },

        syncFromLivewire(value) {
            if (!value) {
                this.selectedOption = null;
                return;
            }

            this.selectedOption = this.options.find(option =>
                option[this.optionValue]?.toString() === value?.toString()
            ) || null;
        },

        updateLivewire() {
            const value = this.selectedOption?.[this.optionValue] || null;

            if (this.wireModel) {
                this.$wire.set(this.wireModel, value, true);
            }
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.searchTerm = '';
                this.$nextTick(() => this.$refs.searchInput.focus());
            }
        },

        selectOption(option) {
            this.selectedOption = option;
            this.isOpen = false;
            this.updateLivewire();
        },

        isSelected(optionId) {
            return this.selectedOption?.[this.optionValue] === optionId;
        },

        selectHighlighted() {
            if (this.highlightedIndex !== null && this.filteredOptions[this.highlightedIndex]) {
                this.selectOption(this.filteredOptions[this.highlightedIndex]);
            }
        }
    }));
});

window.addEventListener("livewire:navigated", () => {
    // Réinitialiser tous les select2 Alpine après navigation
    if (typeof Alpine !== "undefined") {
        Alpine.flushAndStopDeferringMutations();
        Alpine.initTree(document.body);
    }

});
</script>