<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Enum\MaterialRequestStatus;
use App\Enum\RoleEnum;
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
                $query->where('next_approver_role', RoleEnum::GM->value);
            })
            ->when($auth->isDirector(), function ($query) use ($auth) {
                $department = Department::with('users')->where('director_id', $auth->id)->first();
                $query
                    ->whereIn('user_id', $department ? $department->users->pluck('id') : [])
                    ->where('next_approver_role', RoleEnum::DIRECTOR->value)
                ;
            })
            ->when($auth->isHod(), function ($query) use ($auth) {
                $auth->loadMissing('department', 'department.users');
                $query
                    ->whereIn('user_id', $auth->department->users->pluck('id'))
                    ->where('next_approver_role', RoleEnum::HOD->value)
                ;
            })
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
