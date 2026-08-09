<div>
    <div class="flex justify-between items-center border-b border-gray-200 pb-6">
        <h1 class="text-2xl font-bold text-[#0e3a61] tracking-tight">Material Off Site Request Form</h1>
        <a href="{{ route('material.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
            bg-[#0e3a61] text-white text-sm font-medium
            hover:bg-[#0c3253] shadow-sm transition
            focus:outline-none focus:ring-2 focus:ring-white/30">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>

                Back to list
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md mt-6">

        <div class="p-6">
            <form wire:submit.prevent="save" class="space-y-6">

                <div class="w-full">
                    <x-input type="text" wire:model="form.company" name="company" label="Company" place="company" />
                    @error('form.company')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror

                                     <div class="mt-4 w-1/2" x-data="{ mode: @js($personOutMode) }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delegated Person</label>

                        <div class="inline-flex items-center gap-2 mb-2">
                            {{-- Bascule instantanée côté client (Alpine), sans rechargement.
                                 On synchronise le mode + on vide le champ opposé côté serveur
                                 en différé ($wire.set(..., false)) pour que le bon champ soit pris au submit. --}}
                            <button type="button"
                                @click="mode = 'list'; $wire.set('personOutMode', 'list', false); $wire.set('form.person_out_name', '', false)"
                                :class="mode === 'list' ? 'bg-[#0e3a61] text-white border-[#0e3a61]' : 'bg-white text-gray-700 border-gray-300'"
                                class="px-3 py-1 text-xs font-medium rounded-md border">
                                From list
                            </button>
                            <button type="button"
                                @click="mode = 'manual'; $wire.set('personOutMode', 'manual', false); $wire.set('form.person_out_id', '', false)"
                                :class="mode === 'manual' ? 'bg-[#0e3a61] text-white border-[#0e3a61]' : 'bg-white text-gray-700 border-gray-300'"
                                class="px-3 py-1 text-xs font-medium rounded-md border">
                                Enter manually
                            </button>
                        </div>

                        <div x-show="mode === 'list'" wire:key="person-out-list">
                            <x-select2 :options="$users" optionLabel="full_name" wire:model="form.person_out_id"
                                placeholder="Select users" />
                            @error('form.person_out_id')
                                <small class="text-red-500 text-sm">{{ $message }}</small>
                            @enderror
                        </div>

                        <div x-show="mode === 'manual'" x-cloak wire:key="person-out-manual">
                            <input type="text" wire:model="form.person_out_name" placeholder="Enter full name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('form.person_out_name')
                                <small class="text-red-500 text-sm">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Liste des matériels -->
                    <div class="my-6">
                        <h5 class="mb-4 text-lg font-medium">Material infos</h5>
                        @foreach ($form->materials as $index => $material)
                            <div class="grid grid-cols-12 gap-4 mb-3 items-start">
                                <!-- Désignation -->
                                <div class="col-span-3">
                                    <label for="designation"
                                        class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                                    <input type="text" wire:model="form.materials.{{ $index }}.designation"
                                        placeholder="Designation"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error("form.materials.$index.designation")
                                        <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Quantité -->
                                <div class="col-span-3">
                                    <label for="quantity"
                                        class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                    <input type="number" wire:model="form.materials.{{ $index }}.quantity"
                                        placeholder="Quantity"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        min="1">
                                    @error("form.materials.$index.quantity")
                                        <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Additional info -->
                                <div class="col-span-3">
                                    <label for="serial_number"
                                        class="block text-sm font-medium text-gray-700 mb-1">Additional info
                                    </label>
                                    <input type="text" wire:model="form.materials.{{ $index }}.serial_number"
                                        placeholder="Additional info"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error("form.materials.$index.serial_number")
                                        <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Bouton supprimer -->
                                <div class="col-span-2 mt-6">
                                    <button type="button" wire:click="removeMaterial({{ $index }})"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bouton ajouter un matériel -->
                    <div class="mb-6">
                        <button type="button" wire:click="addMaterial"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add field
                        </button>
                    </div>

                    <!-- File upload -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request document (Image)</label>
                        <input type="file" wire:model="form.photos" onchange="previewImages(event)" multiple
                            class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                    </div>

                    <div class="my-4">
                        <div wire:ignore>
                            <div id="preview" class="flex gap-4 mt-4"></div>
                        </div>
                    </div>

                    @error('form.photos.*')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror
                </div>

                <div class="flex justify-center gap-4">
                    <x-form-action cancel="material.index" target="save" />
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImages(event) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';

            Array.from(event.target.files).forEach(file => {
                const wrapper = document.createElement('div');
                wrapper.className =
                    "w-32 h-32 bg-white border border-gray-200 rounded-xl shadow-sm flex items-center justify-center overflow-hidden";

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = "max-w-full max-h-full object-contain";

                wrapper.appendChild(img);
                preview.appendChild(wrapper);
            });
        }
    </script>

</div>
