<div>
    <div class="bg-white rounded-lg shadow-md">

        <div class="flex justify-between items-center border-b border-gray-200 p-4">
            <h1 class="text-2xl font-bold text-[#0e3a61] tracking-tight">Edit Material Off Site Request</h1>
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
        <div class="p-6">
            @if ($materialRequest->isRejected())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        </svg>
                        <div class="text-sm">
                            <p class="font-semibold text-rose-800">This request was rejected.</p>
                            @if ($materialRequest->rejectionReason())
                                <p class="text-rose-700 mt-0.5"><span class="font-medium">Reason:</span> {{ $materialRequest->rejectionReason() }}</p>
                            @endif
                            <p class="text-rose-700/80 mt-1">Correct it below and click <span class="font-semibold">Revise &amp; resubmit</span> — it will restart the approval process.</p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit="save" enctype="multipart/form-data" method="post" class="space-y-6"
                x-data="{ uploading: false, progress: 0 }"
                x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false; progress = 0"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                <p class="text-xs text-slate-400">Fields marked <span class="text-red-500 font-semibold">*</span> are required.</p>

                <div class="w-full">
                    <x-input type="text" wire:model="form.company" name="company" label="Company" place="company" />
                    @error('form.company')
                        <small class="text-red-500 text-sm">{{ $message }}</small>
                    @enderror

                                       <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delegated Person</label>

                        <div class="inline-flex items-center gap-2 mb-2">
                            <button type="button" wire:click="setPersonOutMode('list')"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg border transition {{ $personOutMode === 'list' ? 'bg-[#0e3a61] text-white border-[#0e3a61]' : 'bg-white text-gray-700 border-gray-300' }}">
                                From list
                            </button>
                            <button type="button" wire:click="setPersonOutMode('manual')"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg border transition {{ $personOutMode === 'manual' ? 'bg-[#0e3a61] text-white border-[#0e3a61]' : 'bg-white text-gray-700 border-gray-300' }}">
                                Enter manually
                            </button>
                        </div>

                        @if ($personOutMode === 'list')
                            <div wire:key="person-out-list">
                                <x-select2 :options="$users" optionLabel="full_name" wire:model="form.person_out_id"
                                    placeholder="Select users" />
                                @error('form.person_out_id')
                                    <small class="text-red-500 text-sm">{{ $message }}</small>
                                @enderror
                            </div>
                        @else
                            <div wire:key="person-out-manual">
                                <input type="text" wire:model="form.person_out_name" placeholder="Enter full name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] transition">
                                @error('form.person_out_name')
                                    <small class="text-red-500 text-sm">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Liste des matériels -->
                    <div class="my-6">
                        <h5 class="mb-4 text-base font-semibold text-[#0e3a61] flex items-center gap-2">
                            <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-[#0e3a61]/10 text-[#0e3a61]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </span>
                            Materials <span class="text-red-500 text-sm">*</span>
                        </h5>
                        @foreach ($form->materials as $index => $material)
                            <div class="grid grid-cols-12 gap-4 mb-3 items-start">
                                <!-- Désignation -->
                                <div class="col-span-3">
                                    <label for="designation"
                                        class="block text-sm font-medium text-gray-700 mb-1">Designation <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="form.materials.{{ $index }}.designation"
                                        placeholder="Designation"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] transition">
                                    @error("form.materials.$index.designation")
                                        <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Quantité -->
                                <div class="col-span-3">
                                    <label for="quantity"
                                        class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="form.materials.{{ $index }}.quantity"
                                        placeholder="Quantity"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] transition"
                                        min="1">
                                    @error("form.materials.$index.quantity")
                                        <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-span-3">
                                    <label for="serial_number"
                                        class="block text-sm font-medium text-gray-700 mb-1">Serial
                                        Number</label>
                                    <input type="text" wire:model="form.materials.{{ $index }}.serial_number"
                                        placeholder="serial_number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] transition">
                                    @error("form.materials.$index.serial_number")
                                        <small class="text-red-500 text-sm">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Bouton supprimer -->
                                <div class="col-span-3 mt-6 flex md:justify-start">
                                    <button type="button" wire:click="removeMaterial({{ $index }})"
                                        @disabled(count($form->materials) <= 1)
                                        class="inline-flex items-center justify-center gap-1 px-3 py-2 rounded-md text-sm font-medium text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500 disabled:opacity-40 disabled:cursor-not-allowed transition"
                                        title="Remove this material">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remove
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

                    <!-- Images upload -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Add document(s) (images)</label>

                        {{-- Dropzone (clic OU glisser-déposer) --}}
                        <label for="material-photos"
                            x-data="{ over: false }"
                            x-on:dragover.prevent="over = true"
                            x-on:dragenter.prevent="over = true"
                            x-on:dragleave.prevent="over = false"
                            x-on:drop.prevent="
                                over = false;
                                const input = document.getElementById('material-photos');
                                if ($event.dataTransfer && $event.dataTransfer.files.length) {
                                    input.files = $event.dataTransfer.files;
                                    input.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            "
                            :class="over ? 'border-[#134169] bg-blue-50/60' : 'border-gray-300 bg-gray-50'"
                            class="flex flex-col items-center justify-center w-full border-2 border-dashed rounded-xl px-4 py-6 cursor-pointer hover:border-[#134169] hover:bg-blue-50/40 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                            </svg>
                            <span class="text-sm text-slate-600 mt-2 pointer-events-none"><span class="font-medium text-[#134169]">Click to upload</span> or drag &amp; drop</span>
                            <span class="text-xs text-slate-400 mt-1 pointer-events-none">JPEG, PNG · max 4 MB each · up to 5 images</span>
                            <input id="material-photos" type="file" wire:model="form.photos"
                                accept="image/jpeg,image/png,image/jpg" multiple class="hidden">
                        </label>

                        {{-- Progress bar (pendant l'upload) --}}
                        <div x-show="uploading" x-cloak class="mt-3">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4" />
                                        <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                                    </svg>
                                    Uploading…
                                </span>
                                <span x-text="progress + '%'"></span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full bg-[#134169] transition-all" :style="`width: ${progress}%`"></div>
                            </div>
                        </div>

                        @error('form.photos')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror
                        @error('form.photos.*')
                            <small class="text-red-500 text-sm">{{ $message }}</small>
                        @enderror

                        {{-- Aperçus des nouveaux fichiers téléversés (avec suppression) --}}
                        @if (! empty($form->photos))
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 mt-3">
                                @foreach ($form->photos as $index => $photo)
                                    <div class="relative group aspect-square rounded-xl border border-gray-200 bg-white overflow-hidden"
                                        wire:key="photo-{{ $index }}">
                                        @if (is_object($photo) && method_exists($photo, 'isPreviewable') && $photo->isPreviewable())
                                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" alt="preview">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <button type="button" wire:click="removePhoto({{ $index }})"
                                            class="absolute top-1 right-1 inline-flex items-center justify-center w-6 h-6 rounded-full bg-black/50 text-white hover:bg-red-600 transition"
                                            title="Remove image">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>


                {{-- Documents déjà enregistrés --}}
                @if ($materialRequest->documents && $materialRequest->documents->isNotEmpty())
                    <div class="mt-2">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Existing documents</p>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($materialRequest->documents as $row)
                                <img class="max-w-[300px] h-auto rounded-md border border-gray-200"
                                    src="{{ $row->DocLink() }}" alt="Image">
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="flex justify-center gap-4 mt-6">
                    <x-form-action cancel="material.index" target="save"
                        :label="$materialRequest->isRejected() ? 'Revise & resubmit' : 'Save changes'"
                        :loadingLabel="$materialRequest->isRejected() ? 'Resubmitting…' : 'Saving…'" />
                </div>
            </form>
        </div>
    </div>
</div>
