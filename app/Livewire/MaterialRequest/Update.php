<?php

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Helper\DeleteAction;
use Livewire\WithFileUploads;
use App\Models\MaterialRequest;
use App\Helper\RepeatInputAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Livewire\Forms\MaterialRequestForm;

class Update extends Component
{
    use DeleteAction, WithFileUploads, RepeatInputAction;

    public MaterialRequest $materialRequest;
    public MaterialRequestForm $form;

    public function mount(MaterialRequest $materialRequest)
    {
        $this->materialRequest = $materialRequest;
        $this->form->setmaterialRequest($materialRequest);
        $materialRequest->loadMissing('material_request_items');
        $this->form->materials = $materialRequest->material_request_items
            ->map(fn($item) => [
                'designation'   => $item->designation,
                'quantity'      => $item->quantity,
                'serial_number' => $item->serial_number,
            ])
            ->toArray();
    }

    public function save()
    {
        // Gate::authorize('update-material-request', $this->materialRequest);

        $this->form->update();
    }
}
