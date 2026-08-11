<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Helper\DeleteAction;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\MaterialRequestForm;
use App\Models\MaterialRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Material Off Site Form')]
final class MaterialRequestCreate extends Component
{
    use DeleteAction, RepeatInputAction, WithFileUploads;

        public MaterialRequestForm $form;

    public string $personOutMode = 'list';

    public function mount()
    {
        $this->form->materials = [
            ['designation' => '', 'quantity' => 1, 'serial_number' => ''],
        ];

        // Duplication : ?from=<id> pré-remplit le formulaire depuis une demande visible.
        if ($from = request('from')) {
            $source = MaterialRequest::visibleTo(Auth::user())->find($from);
            if ($source) {
                $this->form->fillFromSource($source);
                if ($this->form->person_out_name && ! $this->form->person_out_id) {
                    $this->personOutMode = 'manual';
                }
            }
        }
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
        $this->form->store();
    }

    public function render()
    {
        $users = User::select('name', 'id', 'badge_number')->get();

        return view('livewire.material-request.material-request-create', compact('users'));
    }
}
