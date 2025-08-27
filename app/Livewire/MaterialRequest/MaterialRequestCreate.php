<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Helper\DeleteAction;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\MaterialRequestForm;
use Livewire\Component;
use Livewire\WithFileUploads;

final class MaterialRequestCreate extends Component
{
    use DeleteAction, RepeatInputAction, WithFileUploads;

    public MaterialRequestForm $form;

    public function mount()
    {
        $this->form->materials = [
            ['designation' => '', 'quantity' => 1, 'serial_number' => ''],
        ];
    }

    public function save()
    {
        $this->form->store();
    }
}
