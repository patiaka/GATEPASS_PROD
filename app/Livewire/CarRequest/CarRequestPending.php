<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Enum\MaterialRequestStatus;
use App\Helper\ApproveAction;
use App\Helper\WithFilter;
use App\Models\CarRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

use function compact;

#[Title('Pending vehicle request')]
final class CarRequestPending extends Component
{
    use ApproveAction, WithFilter;


    public function ResetFilter(): void
    {
        $this->reset('search');
    }

    #[Computed]
    public function rows()
    {
        $auth = Auth::user();

        return CarRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')
            ->when($auth->isGm(), function ($query) use ($auth) {
                $query->where('status', MaterialRequestStatus::Progress)
                    ->whereNotNull('hod_approval_id')
                    // ->orWhere('gm_approval_id', $auth->id)
                    ->orWhere('user_id', $auth->id)->orwhere('status', MaterialRequestStatus::Progress);
            })
            ->when($auth->isHod(), function ($query) use ($auth) {
                $auth->loadMissing('department');
                $users = $auth->department->loadMissing('users');
                $query->where('status', MaterialRequestStatus::Pending)->whereIn('user_id', $users->users->pluck('id'))->orWhere('user_id', $auth->id);
            })

            // ->when($this->department, function ($query) {
            //     $users = Department::with('users')->find($this->department)->users;
            //     $query->whereIn('user_id', $users->pluck('id'));
            // })->when($this->status, function ($query) {
            //     $query->where('status', $this->status);
            // })
			->when($this->search, function ($query) {
                $query->whereAny(['reference', 'status'], 'like', '%' . $this->search . '%');
            })
            ->latest('id')->paginate(10);
    }

    public function render()
    {

        $auth = Auth::user();
        $departments = $auth->isAdmin() ? Department::select('id', 'name')->get() : [];

        return view('livewire.car-request.car-request-pending', compact('departments'));
    }
}
