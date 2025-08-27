<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Helper\DeleteAction;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\MaterialRequestForm;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;

final class MaterialRequestUpdate extends Component
{
    use DeleteAction, RepeatInputAction, WithFileUploads;

    public MaterialRequest $materialRequest;

    public MaterialRequestForm $form;

    public function mount(MaterialRequest $MaterialRequest)
    {
        $this->materialRequest = $MaterialRequest;
        Gate::authorize('update-request', $this->materialRequest);
        $this->form->setMaterialRequest($MaterialRequest);

        $MaterialRequest->loadMissing('material_request_items', 'documents');
        $this->form->materials = $MaterialRequest->material_request_items
            ->map(fn ($item) => [
                'designation' => $item->designation,
                'quantity' => $item->quantity,
                'serial_number' => $item->serial_number,
            ])
            ->toArray();
    }

    public function save()
    {
        Gate::authorize('update-request', $this->materialRequest);

        $this->form->update();
    }
}
