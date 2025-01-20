<?php

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Helper\DeleteAction;
use Livewire\WithFileUploads;
use App\Models\MaterialRequest;
use App\Helper\RepeatInputAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class Update extends Component
{
    use DeleteAction, WithFileUploads, RepeatInputAction;
    public $photos = []; // Tableau pour stocker les fichiers
    public $material;

    public function mount(MaterialRequest $material)
    {
        $material->loadMissing('material_request_items');
        $this->material = $material;
        $material->material_request_items->pluck('quantity', 'designation')->each(function ($quantity, $designation) {
            $this->materials[] = ['designation' => $designation, 'quantity' => $quantity];
        });
    }

    public function save()
    {
        Gate::authorize('update-material-request', $this->material);
        $this->validate([
            'materials' => 'required|array|min:1',
            'materials.*.designation' => 'required|string|min:3',
            'materials.*.quantity' => 'required|integer|min:1',
            'photos.*' => 'nullable|image',
        ]);
        DB::transaction(function () {

            if ($this->photos) {
                $this->file_uplode($this->photos, $this->material);
            }
            // Sync materials
            $this->updateMaterialRequestItems();
            flash('Material request updated successfully');
        });
        return to_route('material.index');
    }

    private function updateMaterialRequestItems(): void
    {
        $this->material->loadMissing('material_request_items');
        // Get the existing items as an associative array
        $existingItems = $this->material->material_request_items->keyBy('id');

        foreach ($this->materials as $material) {
            if (isset($material['id'])) {
                // Update existing item
                $existingItems[$material['id']]->update([
                    'designation' => $material['designation'],
                    'quantity' => $material['quantity'],
                ]);
            } else {
                // Create a new item
                $this->material->material_request_items()->create([
                    'designation' => $material['designation'],
                    'quantity' => $material['quantity'],
                ]);
            }
        }

        // Delete items that were removed
        $toDelete = $existingItems->keys()->diff(collect($this->materials)->pluck('id'));
        $this->material->material_request_items()->whereIn('id', $toDelete)->delete();
    }
}
