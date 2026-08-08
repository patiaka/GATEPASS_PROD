<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Helper\DeleteAction;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\MaterialRequestForm;
use App\Models\MaterialRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Edit material request')]
final class MaterialRequestUpdate extends Component
{
    use DeleteAction, RepeatInputAction, WithFileUploads;

    public MaterialRequest $materialRequest;

        public MaterialRequestForm $form;

    public string $personOutMode = 'list';

    public function mount(MaterialRequest $MaterialRequest)
    {
        $this->materialRequest = $MaterialRequest;
        Gate::authorize('update-request', $this->materialRequest);
        $this->form->setMaterialRequest($MaterialRequest);
        $this->personOutMode = $MaterialRequest->person_out_id || ! $MaterialRequest->person_out_name ? 'list' : 'manual';

        $MaterialRequest->loadMissing('material_request_items', 'documents');

        $this->form->materials = $MaterialRequest->material_request_items
            ->map(fn ($item) => [
                'designation' => $item->designation,
                'quantity' => $item->quantity,
                'serial_number' => $item->serial_number,
            ])
            ->toArray();
    }

       public function setPersonOutMode(string $mode): void
    {
        $this->personOutMode = $mode;

        if ($mode === 'list') {
            $this->form->person_out_name = '';
        } else {
            $this->form->person_out_id = '';
        }
    }

    public function save()
    {
        Gate::authorize('update-request', $this->materialRequest);

        $this->form->update();
    }


    public function render()
    {
        $users = User::select('name', 'id', 'badge_number')->get();

        return view('livewire.material-request.material-request-update', compact('users'));
    }
}
