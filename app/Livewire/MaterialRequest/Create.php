<?php

namespace App\Livewire\MaterialRequest;

use Auth;
use Livewire\Component;
use App\Models\Document;
use App\Helper\DeleteAction;
use App\Jobs\MailRequestJob;
use Livewire\WithFileUploads;
use App\Models\MaterialRequest;
use App\Helper\RepeatInputAction;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use DeleteAction, WithFileUploads, RepeatInputAction;
    public $photos = []; // Tableau pour stocker les fichiers

    public function mount()
    {
        $this->materials = [
            ['designation' => '', 'quantity' => 1], // Un élément initial
        ];
    }

    public function save()
    {
        $this->validate([
            'materials' => 'required|array|min:1',
            'materials.*.designation' => 'required|string|min:3',
            'materials.*.quantity' => 'required|integer|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        DB::transaction(function () {
            $materialRequest = MaterialRequest::create([
                'user_id' => Auth::user()->id,
            ]);

            foreach ($this->photos as $key => $row) {
                $filename = $row->hashName();
                $chemin = $row->storeAs('material/document', $filename, 'public');
                Document::create([
                    'material_request_id' => $materialRequest->id,
                    'chemin' => $chemin,
                ]);
            }
            $materialRequest->material_request_items()->createMany($this->materials);
            $materialRequest->generateId('R');
            MailRequestJob::dispatch($materialRequest, 'vous avez un nouveau request reference' . $materialRequest->reference);
            flash('Material request created successfully');
        });
        return redirect()->route('material.index');
    }
}
