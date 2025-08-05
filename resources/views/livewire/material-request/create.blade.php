<div>
    <div class="bg-white rounded-lg shadow-md">

        <div class="flex justify-between items-center border-b border-gray-200 p-4">
            <h1 class="text-xl font-semibold text-gray-800">Material Off Site Request Form</h1>
            <a href="" class="text-sm text-blue-600 hover:underline bg-white border rounded px-3 py-1 shadow-sm">
                ← Back to list
            </a>
        </div>
        <div class="p-6">
            <form wire:submit="save" enctype="multipart/form-data" method="post" class="space-y-6">
                @csrf
                <div class="w-full">
                    <x-input type="text" wire:model="company" name="company" label="company" place="company" />
                    <!-- Liste des matériels -->
                    <div class="my-6">
                        <h5 class="mb-4 text-lg font-medium">Matérial infos</h5>
                        @foreach ($materials as $index => $material)
                        <div class="grid grid-cols-12 gap-4 mb-3 items-end">
                            <!-- Désignation -->
                            <div class="col-span-3">
                                <label for="designation"
                                    class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                                <input type="text" wire:model="materials.{{ $index }}.designation"
                                    placeholder="Designation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error("materials.$index.designation")
                                <small class="text-red-500 text-sm">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Quantité -->
                            <div class="col-span-3">
                                <label for="quantity"
                                    class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" wire:model="materials.{{ $index }}.quantity" placeholder="Quantity"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    min="1">
                                @error("materials.$index.quantity")
                                <small class="text-red-500 text-sm">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-span-3">
                                <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-1">Serial
                                    Number</label>
                                <input type="text" wire:model="materials.{{ $index }}.serial_number"
                                    placeholder="serial_number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error("materials.$index.serial_number")
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add field
                        </button>
                    </div>

                    <!-- File upload -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request document (Image)</label>
                        <input type="file" wire:model.live="photos" multiple class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                    </div>

                    <div class="my-4">
                        @if ($photos)
                        <div class="flex flex-wrap gap-4">
                            @foreach ($photos as $photo)
                            <img class="max-w-[300px] h-auto rounded-md border border-gray-200"
                                src="{{ $photo->temporaryUrl() }}" alt="Image">
                            @endforeach
                        </div>
                        @endif

                        <div wire:loading wire:target="photos" class="text-center mt-4">
                            <span class="inline-flex items-center text-red-500">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Uploading...
                            </span>
                        </div>
                    </div>

                    @error('photos.*')
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
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
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
</div>