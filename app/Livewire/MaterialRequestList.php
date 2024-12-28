<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Department;
use Livewire\Attributes\On;
use App\Models\MaterialRequest;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;

class MaterialRequestList extends Component
{
    public string $search = "";
    public string $status = "";
    public string $department = "";
    public string $hod_comment = "";
    public string $gm_comment = "";
    public $material;

    public array $selectedRows = [];

    public function updatedSelectedRows($value)
    {

        // This method updates the selected rows state dynamically
    }

    public function selectAll()
    {
        $this->selectedRows = $this->rows->pluck('id')->toArray();
    }

    public function deselectAll()
    {
        $this->selectedRows = [];
    }

    public function bulkDelete()
    {
        MaterialRequest::whereIn('id', $this->selectedRows)->delete();
        $this->reset('selectedRows');
        session()->flash('message', 'Selected rows deleted successfully.');
    }


    public function toggleSelectAll()
    {
        if (count($this->selectedRows) === $this->rows->count()) {
            $this->deselectAll();
        } else {
            $this->selectAll();
        }
    }

    public function show_detail(int $id): void
    {
        $this->material = MaterialRequest::find($id);

        $this->material->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval', 'hodApproval');
        // Dispatch an event to show the modal
        $this->dispatch('show-modal');
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


    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search');
        $this->resetPage();
    }

    #[Computed]
    public function rows()
    {
        return MaterialRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')->when($this->search, function ($query) {
            $query->whereAny(['name', 'email'], 'like', '%' . $this->search . '%');
        })->when($this->department, function ($query) {
            $query->where('department_id', $this->department);
        })->when($this->status, function ($query) {
            $query->where('status', $this->status);
        })->latest('id')->paginate(10);
    }

    public function render()
    {
        $departments = Department::all();
        $users = User::all();
        return view('livewire.material-request-list', \compact('departments', 'users'));
    }
}
