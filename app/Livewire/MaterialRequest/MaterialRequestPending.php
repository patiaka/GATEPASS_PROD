<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Enum\MaterialRequestStatus;
use App\Enum\RoleEnum;
use App\Helper\ApproveAction;
use App\Helper\WithFilter;
use App\Models\Department;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

use function compact;

#[Title('Pending material request')]
final class MaterialRequestPending extends Component
{
    use ApproveAction, WithFilter;

    public $material;

     public function ResetFilter(): void
     {
         $this->reset('search');
    }
    #[Computed]
    public function rows()
    {
        $auth = Auth::user();
        $auth->loadMissing('department', 'department.users');

        $query = MaterialRequest::with('user', 'user.department', 'hodApproval', 'gmApproval');

        if ($auth->isGm()) {
            $query->where(function ($query) use ($auth) {
                $query
                    ->where('next_approver_role', RoleEnum::GM->value)
                    ->orWhere('user_id', $auth->id)
                ;
            });
        }

        if ($auth->isDirector()) {
            $department = Department::with('users')->where('director_id', $auth->id)->first();
            $query->orWhere(function ($q) use ($auth, $department) {
                $q
                    ->whereIn('user_id', $department ? $department->users->pluck('id') : [])
                    ->where('next_approver_role', RoleEnum::DIRECTOR->value)
                    ->orWhere('user_id', $auth->id)
                ;
            });
        }

        if ($auth->isHod()) {
            $query->orWhere(function ($q) use ($auth) {
                $q
                    ->where('next_approver_role', RoleEnum::HOD->value)
                    ->whereIn('user_id', $auth->department->users->pluck('id'))
                ;
            });
        }

        $query->when($this->search, function ($query) {
            $query->whereAny(['reference', 'status'], 'like', '%' . $this->search . '%');
        });

        return $query->latest('id')->paginate(10);
    }

    public function render()
    {
        $auth = Auth::user();
        $departments = $auth->isAdmin() ? Department::select('id', 'name')->get() : [];

        return view('livewire.material-request.material-request-pending', compact('departments'));
    }
}
