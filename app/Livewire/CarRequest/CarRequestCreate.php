<?php

namespace App\Livewire\CarRequest;

use Livewire\Component;
use App\Models\CarRequest;
use App\Jobs\MailRequestJob;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\CarRequestForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CarRequestCreate extends Component
{

    use RepeatInputAction;
    public CarRequestForm $form;

    public function mount()
    {
        $this->form->drivers = [
            ['name' => '', 'contact' => ''], // Un élément initial
        ];

        $this->form->passengers = [
            ['name' => '', 'contact' => ''], // Un élément initial
        ];
    }

    public function save()
    {
        $this->form->store();
    }
}
