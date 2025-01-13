<?php

namespace App\Livewire\CarRequest;

use Livewire\Component;
use App\Models\CarRequest;
use App\Helper\ApproveAction;

class Show extends Component
{
    use ApproveAction;
    public $carRequest;

    public function mount(CarRequest $car)
    {
        $this->carRequest = $car;

        $this->carRequest->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name');
    }

    public function render()
    {
        return view('livewire.car-request.show');
    }
}
