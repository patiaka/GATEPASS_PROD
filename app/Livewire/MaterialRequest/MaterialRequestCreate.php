<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Helper\DeleteAction;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\MaterialRequestForm;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    public function render()
    {
        $users = User::select('name', 'id', 'badge_number')->get();

        return view('livewire.material-request.material-request-create', compact('users'));
    }
}
