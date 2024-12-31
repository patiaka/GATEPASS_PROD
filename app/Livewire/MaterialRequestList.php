<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Department;
use Livewire\Attributes\On;
use App\Helper\ApproveAction;
use App\Models\MaterialRequest;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Model;

class MaterialRequestList extends Component
{
    use ApproveAction;
    public string $search = "";
    public string $status = "";
    public string $user = "";
    public string $department = "";
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




    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search', 'user');
    }

    #[Computed]
    public function rows()
    {
        return MaterialRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')->when($this->search, function ($query) {
            $query->whereAny(['name', 'email'], 'like', '%' . $this->search . '%');
        })->when($this->user, function ($query) {
            $query->where('user_id', $this->user);
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
