<?php

namespace App\Livewire\CarRequest;

use Livewire\Component;
use App\Helper\WithFilter;
use App\Models\CarRequest;
use App\Models\Department;
use App\Helper\ApproveAction;
use Livewire\Attributes\Computed;
use App\Enum\MaterialRequestStatus;
use App\Models\Compagnie;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithFilter, ApproveAction;
    public $car;


    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search', 'compagny');
    }

    #[Computed]
    public function rows()
    {
        $auth = Auth::user();
        return CarRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')
            ->when($auth->isGm(), function ($query) use ($auth) {
                $query->where('status', MaterialRequestStatus::Progress)
                    ->whereNotNull('hod_approval_id')
                    ->orWhere('gm_approval_id', $auth->id)
                    ->orWhere('user_id', $auth->id);
            })
            ->when($auth->isHod(), function ($query) use ($auth) {
                $auth->loadMissing('department');
                $users = $auth->department->loadMissing('users');
                $query->whereIn('user_id', $users->users->pluck('id'))->orWhere('user_id', $auth->id)
                    ->orWhere('hod_approval_id', $auth->id);
            })->when($auth->isUser(), function ($query) use ($auth) {
                $query->where('user_id', $auth->id);
            })->when($this->department, function ($query) {
                $users = Department::with('users')->find($this->department)->users;
                $query->whereIn('user_id', $users->pluck('id'));
            })->when($this->compagny, function ($query) {
                $users = Compagnie::with('users')->find($this->compagny)->users;
                $query->whereIn('user_id', $users->pluck('id'));
            })->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })->when($this->search, function ($query) {
                $query->whereAny(['reference', 'status'], 'like', '%' . $this->search . '%');
            })->latest('id')->paginate(10);
    }

    public function render()
    {
        $auth = Auth::user();
        $departments = $auth->isAdmin() ? Department::select('id', 'name')->get() : [];
        $compagnies = $auth->isAdmin() ? Compagnie::select('id', 'name')->get() : [];
        return view('livewire.car-request.index', \compact('departments', 'compagnies'));
    }
}
