<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MaterialRequest;

class MaterialRequestShow extends Component
{
    public $material;
    public function mount(MaterialRequest $material)
    {
        $this->material = $material;

        $this->material->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval', 'hodApproval');
    }

    public function approveByGm(int $id): void
    {
        $request =  MaterialRequest::find($id);
        $request->update([
            'gm_comment' => $this->gm_comment,
            'gm_approval_date' => now(),
            'gm_approval_id' => 1
        ]);
        flash('Material request approv successfully');
    }

    public function approveByHod(int $id): void
    {
        $request =  MaterialRequest::find($id);
        $request->update([
            'hod_comment' => $this->hod_comment,
            'hod_approval_date' => now(),
            'hod_approval_id' => 1
        ]);

        flash('Material request approved successfully');
    }

    public function render()
    {
        return view('livewire.material-request-show');
    }
}
