<?php

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Helper\ApproveAction;
use App\Models\MaterialRequest;

class Show extends Component
{
    use ApproveAction;
    public $material;

    public function mount(MaterialRequest $material)
    {
        $this->material = $material;

        $this->material->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name');
    }


    public function render()
    {
        return view('livewire.material-request.show');
    }
}
