<?php

namespace App\Livewire\CarRequest;

use Livewire\Component;
use App\Models\CarRequest;
use App\Helper\ApproveAction;

class CarRequestShow extends Component
{
    use ApproveAction;
    public CarRequest $carRequest;

    public function mount(CarRequest $CarRequest)
    {
        $this->carRequest = $CarRequest;

        $this->carRequest->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name', 'car_drivers', 'passengers');
    }

    public function render()
    {
        return view('livewire.car-request.car-request-show');
    }
}
