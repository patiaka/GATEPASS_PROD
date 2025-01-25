<?php

namespace App\Livewire\MaterialRequest;

use Auth;
use App\Models\User;
use Livewire\Component;
use App\Helper\WithFilter;
use App\Models\Department;
use App\Helper\ApproveAction;
use App\Models\MaterialRequest;
use Livewire\Attributes\Computed;
use App\Enum\MaterialRequestStatus;

class Index extends Component
{
    use WithFilter, ApproveAction;
    public $material;

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
                $query->whereIn('user_id', $users->users->pluck('id'))
                    ->orWhere('user_id', $auth->id)
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
