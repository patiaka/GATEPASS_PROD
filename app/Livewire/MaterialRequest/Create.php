<?php

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Models\Document;
use App\Helper\DeleteAction;
use App\Jobs\MailRequestJob;
use Livewire\WithFileUploads;
use App\Models\MaterialRequest;
use App\Helper\RepeatInputAction;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use DeleteAction, WithFileUploads, RepeatInputAction;
    public $photos = []; // Tableau pour stocker les fichiers
    #[Validate('required|string|min:3')]
    public $company = '';

    public function mount()
    {
        $this->materials = [
            ['designation' => '', 'quantity' => 1, 'serial_number' => ''],
        ];
    }

    public function save()
    {
        $this->validate([
            'materials' => 'required|array|min:1',
            'materials.*.serial_number' => 'nullable|string|min:3',
            'materials.*.designation' => 'required|string|min:3',
            'materials.*.quantity' => 'required|integer|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        DB::transaction(function () {
            // $materialRequest = MaterialRequest::create([
            //     'user_id' => Auth::user()->id,
            // ]);
            $materialRequest = Auth::user()->material_requests()->create(['company' => $this->company]);
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
            MailRequestJob::dispatch($materialRequest, 'Awaiting a material gate pass request to approve reference' . $materialRequest->reference);
            flash('Material request created successfully');
        });
        return redirect()->route('material.index');
    }
}
