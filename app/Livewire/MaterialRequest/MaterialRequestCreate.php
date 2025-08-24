<?php

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Helper\DeleteAction;
use Livewire\WithFileUploads;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\MaterialRequestForm;

class MaterialRequestCreate extends Component
{
    use DeleteAction, WithFileUploads, RepeatInputAction;

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
