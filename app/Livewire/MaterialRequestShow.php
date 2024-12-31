<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helper\ApproveAction;
use App\Models\MaterialRequest;

class MaterialRequestShow extends Component
{
    use ApproveAction;
    public $material;

    public function mount(MaterialRequest $material)
    {
        $this->material = $material;

        $this->material->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval', 'hodApproval');
    }


    public function render()
    {
        return view('livewire.material-request-show');
    }
}
