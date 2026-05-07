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

                    <div class="mt-4 w-1/2">
                        <x-select2 :options="$users" optionLabel="full_name" wire:model="form.person_out_id"
                            label="Name" placeholder="Select users" required="true"/>
                        @error('form.person_out_id')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Liste des matériels -->
                    <div class="my-6">
                        <h5 class="mb-4 text-lg font-medium">Material infos</h5>
                        @foreach ($form->materials as $index => $material)
                            <div class="grid grid-cols-12 gap-4 mb-3 items-end">
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
                                <div class="col-span-2">
                                    <button type="button" wire:click="removeMaterial({{ $index }})"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
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

                <div class="flex justify-center gap-4 mt-6">
                    <a href="{{ route('material.index') }}" role="button"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#0e3a61] hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">Validate</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                    </button>
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
