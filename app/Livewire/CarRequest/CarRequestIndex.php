<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Enum\MaterialRequestStatus;
use App\Enum\RoleEnum;
use App\Helper\ApproveAction;
use App\Helper\WithFilter;
use App\Models\CarDriver;
use App\Models\CarRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('All vehicle request')]
final class CarRequestIndex extends Component
{
    use ApproveAction, WithFilter;

    #[Url(as: 'by_status')]
    public ?string $by_status = null;

    public $car;

    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search');
    }

    #[Computed]
    public function rows()
    {
        $auth = Auth::user();

        return CarRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')
            // Filters
            ->when($this->department, function ($query) {
                $users = Department::with('users')->find($this->department)->users;
                $query->whereIn('user_id', $users->pluck('id'));
            })
            ->when($this->by_status, function ($query) {
                $query->where('status', $this->by_status);
            })
            ->when($this->search, function ($query) {
                $query->whereAny(['reference', 'status'], 'like', '%'.$this->search.'%');
            })
            
            // GM
            ->when($auth->isGm(), function ($query) use ($auth) {
                $query->where(function ($query) use ($auth) {
                    $query 
                        ->where('status', MaterialRequestStatus::Approved)
                        ->orWhere('status', MaterialRequestStatus::Rejected)
                        ->where('gm_approval_id', '!=', null)
                        ->orWhere('user_id', $auth->id)
                    ;
                });
            })
            
            // DIRECTOR
            ->when($auth->isDirector(), function ($query) use ($auth) {
                $department = Department::with('users')->where('director_id', $auth->id)->first();
                $query->where(function ($query) use ($department, $auth) {
                    $query
                        ->whereIn('user_id', $department ? $department->users->pluck('id') : [])
                        ->orWhere('user_id', $auth->id)
                    ;
                });
            })

            // HOD
            ->when($auth->isHod(), function ($query) use ($auth) {
                $auth->loadMissing('department', 'department.users');
                $query->where(function ($query) use ($auth) {
                    $query->whereIn('user_id', $auth->department->users->pluck('id'));
                });
            })

            // User
            ->when($auth->isSimpleUser(), function ($query) use ($auth) {
                $query->where('user_id', $auth->id);
            })

            // Security
            ->when($auth->isSecurity(), function ($query) use ($auth) {
                $query->where('status', MaterialRequestStatus::Approved)->orWhere('user_id', $auth->id);
            })
            ->latest('id')
            ->paginate(10)
        ;
    }

    public function delete(int $id): void
    {
        $row = CarRequest::find($id);

        if (! $row) {
            flash()->error('Car request not found.');

            return;
        }

        Gate::authorize('delete-request', $row);

        CarDriver::where('car_request_id', $row->id)->delete();

        $row->delete();
        flash()->success('Car request deleted with success');
    }

    public function render()
    {
        $auth = Auth::user();
        $departments = $auth->isAdmin() ? Department::select('id', 'name')->get() : [];

        return view('livewire.car-request.car-request-index', compact('departments'));
    }
}
