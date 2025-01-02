<?php

namespace App\Livewire;

use App\Enum\MaterialRequestStatus;
use App\Models\User;
use Livewire\Component;
use App\Models\Department;
use Livewire\Attributes\On;
use App\Helper\ApproveAction;
use App\Models\MaterialRequest;
use Auth;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Model;
use Request;

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

    public function selectAll(): void
    {
        $this->selectedRows = $this->rows->pluck('id')->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectedRows = [];
    }

    public function bulkDelete(): void
    {
        MaterialRequest::whereIn('id', $this->selectedRows)->delete();
        $this->reset('selectedRows');
        session()->flash('message', 'Selected rows deleted successfully.');
    }


    public function toggleSelectAll(): void
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
        $auth = Auth::user();
        return MaterialRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')
            ->when($auth->isGm(), function ($query) use ($auth) {
                $query->where('status', MaterialRequestStatus::Progress)
                    ->whereNotNull('hod_approval_id')
                    ->orWhere('gm_approval_id', $auth->id)
                    ->orWhere('user_id', $auth->id);
            })
            ->when($auth->isHod(), function ($query) use ($auth) {
                $auth->loadMissing('department');
                $users = $auth->department->loadMissing('users');
                $query->whereIn('user_id', $users->pluck('id'))->orWhere('user_id', $auth->id)
                    ->orWhere('hod_approval_id', $auth->id);
            })->when($auth->isUser(), function ($query) use ($auth) {
                $query->where('user_id', $auth->id);
            })->when($this->search, function ($query) {
                $query->whereAny(['reference', 'status'], 'like', '%' . $this->search . '%');
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
        $auth = Auth::user();
        $departments = !$auth->isAdmin() ? Department::all() : [];
        $users = User::when($auth->isHod(), function ($query) use ($auth) {
            $query->whereIn('department_id', $auth->department_id);
        })->when($auth->isUser(), function ($query) {
            $query = [];
        });
        return view('livewire.material-request-list', \compact('departments', 'users'));
    }
}
