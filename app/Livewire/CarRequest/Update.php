<?php

namespace App\Livewire\CarRequest;

use Gate;
use Livewire\Component;
use App\Models\CarRequest;
use App\Helper\RepeatInputAction;
use App\Livewire\Forms\CarRequestForm;

class Update extends Component
{

    use RepeatInputAction;

    public CarRequest $carRequest;

    public CarRequestForm $form;


    public function mount(CarRequest $CarRequest)
    {
        $this->carRequest = $CarRequest;
        Gate::authorize('update-car-request', $this->carRequest);
        $this->form->setCarRequest($CarRequest);

        $CarRequest->loadMissing('car_drivers', 'passengers');

        $CarRequest->car_drivers->pluck('name', 'contact')->each(function ($name, $contact) {
            $this->form->drivers[] = ['name' => $name, 'contact' => $contact];
        });

        $CarRequest->passengers->pluck('name', 'contact')->each(function ($name, $contact) {
            $this->form->passengers[] = ['name' => $name, 'contact' => $contact];
        });
    }



    public function save()
    {
        Gate::authorize('update-car-request', $this->carRequest);
        $this->form->update();
    }
}
