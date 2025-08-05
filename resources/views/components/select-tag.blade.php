@props([
'options' => [],
'placeholder' => 'Select options',
'name' => '',
'label' => null,
'labelClass' => '',
'required' => false,
'sm' => false,
'selectedOptions' => [],
'optionValue' => 'id',
'optionLabel' => 'nom',
'asyncUrl' => null,
'maxSelections' => null,
])

<div x-data="multiSelect({
        options: @js($options),
        placeholder: '{{ $placeholder }}',
        value: @entangle($attributes->wire('model')),
        wireModel: '{{ $attributes->wire('model')->value() }}',
        optionValue: '{{ $optionValue }}',
        optionLabel: '{{ $optionLabel }}',
        asyncUrl: '{{ $asyncUrl }}',
        maxSelections: {{ $maxSelections ?? 'null' }},
        initialSelected: @js($selectedOptions)
    })" wire:ignore.self class="space-y-3 relative" @click.stop @keydown.escape.window="closeDropdown()"
    x-init="init()">

    @if($label)
    <label for="{{ $name }}" class="label-text uppercase text-xs font-bold text-gray-700 {{ $labelClass }}">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <div class="relative" x-ref="container">

        <!-- Select Button -->
        <div @click="toggleDropdown()" @keydown.enter.prevent="toggleDropdown()"
            @keydown.space.prevent="toggleDropdown()" @keydown.arrow-down.prevent="openDropdown(); highlightNext()"
            @keydown.arrow-up.prevent="openDropdown(); highlightPrev()" tabindex="0"
            @class([ 'flex items-center justify-between w-full px-4 py-2 mb-3 text-left bg-white border cursor-pointer input focus:outline-none focus:ring-2 focus:ring-blue-500'
            , 'input-sm'=> $sm,
            'border-error' => $errors->has($attributes->wire('model')->value())
            ])
            :class="{
            'border-blue-500 ring-2 ring-blue-100': isOpen,
            'border-gray-300 hover:border-gray-400': !isOpen
            }">

            <div class="flex flex-wrap gap-1.5 flex-1 min-h-[1.5rem]">
                <template x-if="selectedOptions.length === 0">
                    <span class="text-gray-500" x-text="placeholderText"></span>
                </template>

                <!-- Tags avec animations -->
                <template x-for="option in selectedOptions" :key="option[optionValue]">
                    <span x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="inline-flex items-center text-blue-800 text-sm py-0.5 pl-2 pr-1">
                        <span class="truncate max-w-[120px]" x-text="option[optionLabel]"
                            :title="option[optionLabel]"></span>
                        <button @click.stop="removeOption(option[optionValue])"
                            @keydown.enter.stop.prevent="removeOption(option[optionValue])"
                            class="ml-1 p-0.5 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded focus:outline-none focus:bg-blue-100"
                            tabindex="-1" type="button">×</button>
                    </span>
                </template>
            </div>

            <svg class="h-5 w-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                :class="{ 'rotate-180': isOpen }" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <!-- Dropdown Menu avec position intelligente -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1" @click.outside="closeDropdown()"
            @keydown.escape="closeDropdown()" :class="dropdownPosition"
            class="absolute z-50 w-full bg-white border border-gray-200 shadow-lg rounded-md">

            <!-- Search Input -->
            <div class="p-2 border-b border-gray-100">
                <input x-ref="searchInput" x-model="searchTerm" @keydown.enter.prevent="selectHighlighted()"
                    @keydown.arrow-down.prevent="highlightNext()" @keydown.arrow-up.prevent="highlightPrev()"
                    @keydown.escape="closeDropdown()" @input.debounce.300ms="handleSearch()" type="text"
                    class="w-full px-3 py-2 border border-gray-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    placeholder="Rechercher...">
            </div>

            <!-- Selection Info -->
            <template x-if="maxSelections && selectedOptions.length >= maxSelections">
                <div class="px-3 py-2 text-xs text-orange-600 bg-orange-50 border-b border-orange-100">
                    <div class="flex items-center gap-1">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Maximum <span x-text="maxSelections"></span> selections reached
                    </div>
                </div>
            </template>

            <!-- Options List -->
            <div class="max-h-60 overflow-y-auto py-1" x-ref="optionsList">
                <template x-if="isLoading">
                    <div class="flex justify-center p-4">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <span class="loading loading-spinner loading-sm"></span>
                            Loading...
                        </div>
                    </div>
                </template>

                <template x-if="!isLoading && filteredOptions.length === 0">
                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                        <div class="flex flex-col items-center gap-1">
                            <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 6c-4.411 0-8 3.589-8 8 0 1.76.57 3.386 1.536 4.708l.172.192" />
                            </svg>
                            <span x-text="searchTerm ? 'No results found for \"' + searchTerm + ' \"'
                                : 'No options available'"></span>
                        </div>
                    </div>
                </template>

                        <template x-if=" filteredOptions.length===0">
                                <div class="px-4 py-2 text-sm text-gray-500">Aucun résultat</div>
                </template>

                <template x-for=" (option, index) in filteredOptions" :key="option[optionValue]">
                    <div @click="toggleOption(option)" @mouseenter="highlightedIndex = index"
                        @keydown.enter.prevent="toggleOption(option)" :id="'option-' + option[optionValue]"
                        tabindex="-1"
                        class="px-4 py-2.5 text-sm cursor-pointer flex items-center justify-between transition-colors duration-150"
                        :class="{
                                 'bg-blue-100 text-blue-800 font-medium': isSelected(option[optionValue]),
                             'bg-gray-100': highlightedIndex === index && !isSelected(option[optionValue]),
                             'opacity-40 cursor-not-allowed': maxSelections && selectedOptions.length >= maxSelections && !isSelected(option[optionValue])
                         }">
                        <span class="truncate flex-1" x-text="option[optionLabel]"></span>
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

            <!-- Footer with shortcuts info -->
            <div class="px-3 py-2 text-xs text-gray-400 bg-gray-50 border-t border-gray-100 rounded-b-md">
                <div class="flex justify-between items-center">
                    <span>Use ↑↓ to navigate, Enter to select, Esc to close</span>
                    <span x-show="selectedOptions.length > 0" x-text="selectedOptions.length + ' selected'"></span>
                </div>
            </div>
        </div>

        @error($attributes->wire('model')->value())
        <span class="text-error text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('multiSelect', (config) => ({
            // Configuration
            options: config.options || [],
        placeholderText: config.placeholder || 'Select options',
        value: config.value || [],
        wireModel: config.wireModel || '',
        optionValue: config.optionValue || 'id',
        optionLabel: config.optionLabel || 'nom',
        asyncUrl: config.asyncUrl || null,
        maxSelections: config.maxSelections || null,
        initialSelected: config.initialSelected || [],

        // State
        isOpen: false,
        isLoading: false,
        searchTerm: '',
        selectedOptions: [],
        highlightedIndex: null,

        init() {
            // 1. Initialiser avec les options sélectionnées passées en prop
            if (this.initialSelected && this.initialSelected.length > 0) {
                this.selectedOptions = this.initialSelected;
                this.updateLivewire();
            }
            // 2. Sinon, utiliser la valeur de Livewire
            else if (this.value) {
                this.syncFromLivewire(this.value);
            }

            // Surveiller les changements de valeur Livewire
            this.$watch('value', (value) => {
                this.syncFromLivewire(value);
            });
        },

        // CORRECTION PRINCIPALE ICI : Utilisation de 'value' au lieu de 'livewireValue'
        syncFromLivewire(value) {
            if (!value || value.length === 0) {
                this.selectedOptions = [];
                return;
            }

            const selectedValues = Array.isArray(value) ? value : [value];
            this.selectedOptions = this.options.filter(option =>
                selectedValues.includes(option[this.optionValue])
            );
        },

         updateLivewire() {
            const selectedValues = this.selectedOptions.map(option =>
                option[this.optionValue]
            );

            if (this.wireModel) {
                this.$wire.set(this.wireModel, selectedValues, true);
            }
        },

            get dropdownPosition() {
                // Position intelligente du dropdown
                return {
                    'mt-1': true, // Position par défaut
                    // Vous pouvez ajouter une logique plus complexe ici
                    // pour détecter si le dropdown dépasse l'écran
                };
            },

            get filteredOptions() {
                if (!this.searchTerm) return this.options;

                return this.options.filter(option => {
                    const label = option[this.optionLabel]?.toString().toLowerCase() || '';
                    return label.includes(this.searchTerm.toLowerCase());
                });
            },

            toggleDropdown() {
                if (this.isOpen) {
                    this.closeDropdown();
                } else {
                    this.openDropdown();
                }
            },

            openDropdown() {
                this.isOpen = true;
                this.searchTerm = '';
                this.highlightedIndex = null;
                this.$nextTick(() => {
                    this.$refs.searchInput?.focus();
                });
            },

            closeDropdown() {
                this.isOpen = false;
                this.searchTerm = '';
                this.highlightedIndex = null;
            },

            highlightNext() {
                if (this.filteredOptions.length === 0) return;

                if (this.highlightedIndex === null || this.highlightedIndex >= this.filteredOptions.length - 1) {
                    this.highlightedIndex = 0;
                } else {
                    this.highlightedIndex++;
                }
                this.scrollToHighlighted();
            },

            highlightPrev() {
                if (this.filteredOptions.length === 0) return;

                if (this.highlightedIndex === null || this.highlightedIndex <= 0) {
                    this.highlightedIndex = this.filteredOptions.length - 1;
                } else {
                    this.highlightedIndex--;
                }
                this.scrollToHighlighted();
            },

            scrollToHighlighted() {
                if (this.highlightedIndex === null) return;

                this.$nextTick(() => {
                    const highlightedOption = this.$refs.optionsList?.querySelector(`#option-${this.filteredOptions[this.highlightedIndex][this.optionValue]}`);
                    if (highlightedOption) {
                        highlightedOption.scrollIntoView({
                            block: 'nearest',
                            behavior: 'smooth'
                        });
                    }
                });
            },

            selectHighlighted() {
                if (this.highlightedIndex !== null && this.filteredOptions[this.highlightedIndex]) {
                    this.toggleOption(this.filteredOptions[this.highlightedIndex]);
                }
            },
            toggleOption(option) {
                // Vérifier si le maximum de sélections est atteint
                if (this.maxSelections &&
                    this.selectedOptions.length >= this.maxSelections &&
                    !this.isSelected(option[this.optionValue])) {
                    return;
                }

                const optionId = option[this.optionValue];
                const index = this.selectedOptions.findIndex(o =>
                    o[this.optionValue] === optionId
                );

                if (index === -1) {
                    this.selectedOptions.push(option);
                } else {
                    this.selectedOptions.splice(index, 1);
                }

                this.updateLivewire();
            },

            removeOption(optionId) {
                this.selectedOptions = this.selectedOptions.filter(o =>
                    o[this.optionValue] !== optionId);
                this.updateLivewire();
            },

            isSelected(optionId) {
                return this.selectedOptions.some(o =>
                    o[this.optionValue] === optionId);
            },

            async handleSearch() {
                if (this.asyncUrl && this.searchTerm.length >= 2) {
                    await this.loadAsyncOptions(this.searchTerm);
                }
            },

            // Dans la méthode loadAsyncOptions
            async loadAsyncOptions(search = '') {
                if (!this.asyncUrl) return;

                this.isLoading = true;
                try {
                    const url = new URL(this.asyncUrl);
                    if (search) url.searchParams.set('search', search);

                    // Inclure les IDs sélectionnés dans la requête
                    if (this.selectedOptions.length > 0) {
                        url.searchParams.set('selected', this.selectedOptions.map(o => o[this.optionValue]).join(','));
                    }

                    const response = await fetch(url);
                    this.options = await response.json();

                    // Resynchroniser après chargement
                    this.syncFromLivewire(this.value);
                } catch (error) {
                    console.error('Error loading options:', error);
                } finally {
                    this.isLoading = false;
                }
            }
        }));
    });
</script>