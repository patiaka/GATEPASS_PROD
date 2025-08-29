<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Helper\DeleteAction;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\MaterialRequestForm;

#[Title('Material Off Site Form')]
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
