<?php

namespace App\Livewire\MaterialRequest;

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

class Index extends Component
{
    public string $search = "";
    public string $status = "";
    public string $department = "";
    public array $selectedRows = [];
    public $material;


    public function selectAll(): void
    {
        $this->selectedRows = $this->rows->pluck('id')->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectedRows = [];
    }

    public function bulkAction(string $action): void
    {
        if ($action === 'reject') {
            MaterialRequest::whereIn('id', $this->selectedRows)->update(['status' => MaterialRequestStatus::Rejected]);
        } elseif ($action === 'approve') {
            Auth::user()->isHod() ?
                MaterialRequest::whereIn('id', $this->selectedRows)->update(['status' => MaterialRequestStatus::Progress]) : MaterialRequest::whereIn('id', $this->selectedRows)->update(['status' => MaterialRequestStatus::Approved]);
        }
        $this->reset('selectedRows');
        flash($action . ' applied rows successfully.');
    }


    public function toggleSelectAll(): void
    {
        if (count($this->selectedRows) === $this->rows->count()) {
            $this->deselectAll();
        } else {
            $this->selectAll();
        }
    }


    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search');
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
        return view('livewire.material-request.index', \compact('departments'));
    }
}
