<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helper\DeleteAction;
use Livewire\WithFileUploads;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\DB;

class MaterialRequestCreate extends Component
{
    use DeleteAction, WithFileUploads;
    public string $comment = '';
    public array $materials = []; // Tableau pour stocker les matériels
    public $photos = []; // Tableau pour stocker les fichiers

    public function mount()
    {
        $this->materials = [
            ['designation' => '', 'quantity' => 1], // Un élément initial
        ];
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
            'photos.*' => 'required|image',
        ]);

        DB::transaction(function () {
            $materialRequest = MaterialRequest::create([
                'user_id' => 1,
                'comment' => $this->comment,
            ]);

            $this->file_uplode($this->photos, $materialRequest);
            $materialRequest->material_request_items()->createMany($this->materials);
            $materialRequest->generateId('R');
            flash('Material request created successfully');
        });
        return redirect()->route('material.index');
    }

    public function render()
    {
        return view('livewire.material-request-create');
    }
}
