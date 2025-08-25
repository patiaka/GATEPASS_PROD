<?php

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Helper\ApproveAction;
use App\Models\MaterialRequest;

class MaterialRequestShow extends Component
{
    use ApproveAction;
    public $MaterialRequest;

    public function mount(MaterialRequest $MaterialRequest)
    {
        $this->MaterialRequest = $MaterialRequest;

        $this->MaterialRequest->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name', 'documents');
    }


    public function render()
    {
        return view('livewire.material-request.material-request-show');
    }
}
