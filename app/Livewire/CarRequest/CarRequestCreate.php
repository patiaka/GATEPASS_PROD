<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Helper\RepeatInputAction;
use App\Livewire\Forms\CarRequestForm;
use Livewire\Component;

final class CarRequestCreate extends Component
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
