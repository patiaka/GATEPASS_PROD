<div>
    <div class="card">
        <h2 class="p-4 text-center">Form of update material request</h2>
        <div class="card-body">
            <form wire:submit.prevent="save" enctype="multipart/form-data" method="post">
                @csrf
                <div class="col-md-12">
                    <!-- Liste des matériels -->
                    <div class="mb-4">
                        <h5 class="mb-3">Matérial infos</h5>
                        @foreach ($materials as $index => $material)
                        <div class="row mb-2">
                            <!-- Désignation -->
                            <div class="col-md-6">

                                <input type="text" wire:model="materials.{{ $index }}.designation"
                                    placeholder="Designation" class="form-control">
                                @error("materials.$index.designation")
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Quantité -->
                            <div class="col-md-4">

                                <input type="number" wire:model="materials.{{ $index }}.quantity" placeholder="Quantity"
                                    class="form-control" min="1">
                                @error("materials.$index.quantity")
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Bouton supprimer -->
                            <div class="col-md-2">
                                <button type="button" wire:click="removeMaterial({{ $index }})"
                                    class="btn btn-danger w-100">
                                    <i class="bx bx-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Bouton ajouter un matériel -->
                    <div class="mb-4">
                        <button type="button" wire:click="addMaterial" class="btn btn-success">
                            <i class="bx bx-plus"></i>
                            Add field
                        </button>
                    </div>
                    <x-input label="Request document" wire:model="photos" type="file" multiple :required="false" />
                    @error('photos.*')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>
                <div class="modal-footer mt-2 justify-content-center">

                    <a href="{{ route('material.index') }}" role="button" class="btn btn-outline-danger">
                        Cancel
                    </a>
                    <button class="mx-2 btn btn-success" type="submit">Validate</button>
                </div>
            </form>
        </div>
    </div>
</div>