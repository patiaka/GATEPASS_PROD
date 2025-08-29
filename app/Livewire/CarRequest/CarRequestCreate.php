<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\CarRequestForm;

#[Title('Vehicle Off Site Form')]
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
