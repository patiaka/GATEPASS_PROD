<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Enum\MaterialRequestStatus;
use App\Enum\RoleEnum;
use App\Helper\ApproveAction;
use App\Helper\DeleteAction;
use App\Helper\WithFilter;
use App\Models\Department;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

use function compact;

#[Title('All material requests')]
final class MaterialRequestIndex extends Component
{
    use ApproveAction, DeleteAction, WithFilter;

    #[Url(as: 'by_status')]
    public ?string $by_status = null;

    public $material;

    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search', 'compagny');
    }

    #[Computed]
    public function rows()
    {
        $auth = Auth::user();

        return MaterialRequest::with(['user.department', 'hodApproval', 'gmApproval'])
            // ---- FILTERS ----
            ->when($this->department, function ($query) {
                $users = Department::with('users')->find($this->department)?->users ?? collect();
                $query->whereIn('user_id', $users->pluck('id'));
            })
            ->when($this->by_status, function ($query) {
                $query->where('status', $this->by_status);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%'.$this->search.'%')
                        ->orWhere('status', 'like', '%'.$this->search.'%');
                });
            })
            
            // ---- GM ----
            ->when($auth->isGm(), function ($query) use ($auth) {
                $query
                    ->where('user_id', $auth->id)
                    ->orWhere(function ($q) {
                        $q
                            ->where('status', MaterialRequestStatus::Approved)
                            ->orWhere('status', MaterialRequestStatus::Rejected)
                            ->where('gm_approval_id', '!=', null)
                        ;
                    })
                ;
            })

            // ---- DIRECTOR ----
            ->when($auth->isDirector(), function ($query) use ($auth) {
                $department = Department::with('users')->where('director_id', $auth->id)->first();
                $query
                    ->whereIn('user_id', $department ? $department->users->pluck('id') : [])
                    ->orWhere('user_id', $auth->id)
                ;
            })

            // ---- HOD ----
            ->when($auth->isHod(), function ($query) use ($auth) {
                $auth->loadMissing('department');
                $users = $auth->department->loadMissing('users');
                $query
                    ->whereIn('user_id', $users->users->pluck('id'))
                    ->orWhere('user_id', $auth->id)
                ;
            })

            // ---- USER ----
            ->when($auth->isUser(), function ($query) use ($auth) {
                $query->where('user_id', $auth->id);
            })

            // ---- SECURITY ----
            ->when($auth->isSecurity(), function ($query) use ($auth) {
                $query->where(function ($q) use ($auth) {
                    $q->where('status', MaterialRequestStatus::Approved)
                        ->orWhere('user_id', $auth->id);
                });
            })
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function delete(int $id): void
    {

        $row = MaterialRequest::find($id);
        Gate::authorize('delete-request', $row);

        if (! $row) {
            flash()->error('Material request not found.');

            return;
        }

        $row->loadMissing('documents');

        if ($row->documents) {
            foreach ($row->loadMissing('documents')->documents as $row) {
                $this->file_delete($row);
            }
            $row->documents->delete();
        }
        
        $row->delete();
        flash()->success('Material request deleted with success');
    }

    public function render()
    {
        $auth = Auth::user();
        $departments = $auth->isAdmin() ? Department::select('id', 'name')->get() : [];

        return view('livewire.material-request.material-request-index', compact('departments'));
    }
}
