<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helper\DeleteAction;
use Livewire\WithFileUploads;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\DB;

class MaterialRequestUpdate extends Component
{
    use DeleteAction, WithFileUploads;
    public string $comment = '';
    public array $materials = []; // Tableau pour stocker les matériels
    public $photos = []; // Tableau pour stocker les fichiers
    public $material;

    public function mount(MaterialRequest $material)
    {
        $material->loadMissing('material_request_items');
        $this->material = $material;
        $this->comment = $material->comment;
        $material->material_request_items->pluck('quantity', 'designation')->each(function ($quantity, $designation) {
            $this->materials[] = ['designation' => $designation, 'quantity' => $quantity];
        });
    }

    public function addMaterial(): void
    {
        $this->materials[] = ['designation' => '', 'quantity' => 1];
    }

    public function removeMaterial($index): void
    {
        unset($this->materials[$index]);
        $this->materials = array_values($this->materials); // Réindexer le tableau
    }

    public function save()
    {
        $this->validate([
            'comment' => 'nullable|string',
            'materials' => 'required|array|min:1',
            'materials.*.designation' => 'required|string|min:3',
            'materials.*.quantity' => 'required|integer|min:1',
            'photos.*' => 'nullable|image',
        ]);
        DB::transaction(function () {
            $this->material->update(['comment' => $this->comment]);

            if ($this->photos) {
                $this->file_uplode($this->photos, $this->material);
            }
            // Sync materials
            $this->updateMaterialRequestItems();
            flash('Material request updated successfully');
        });
        return redirect()->route('material.index');
    }

    private function updateMaterialRequestItems()
    {
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

    public function render()
    {
        return view('livewire.material-request-update');
    }
}
