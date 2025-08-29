<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use function compact;
use Livewire\Component;
use App\Helper\WithFilter;
use App\Models\Department;
use App\Helper\DeleteAction;
use App\Helper\ApproveAction;
use Livewire\Attributes\Title;
use App\Models\MaterialRequest;
use Livewire\Attributes\Computed;
use App\Enum\MaterialRequestStatus;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

#[Title('All material request')]
final class MaterialRequestIndex extends Component
{
    use ApproveAction, DeleteAction, WithFilter;

    public $material;

    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search', 'compagny');
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
            })->when($this->department, function ($query) {
                $users = Department::with('users')->find($this->department)->users;
                $query->whereIn('user_id', $users->pluck('id'));
            })->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })->when($this->search, function ($query) {
                $query->whereAny(['reference', 'status'], 'like', '%' . $this->search . '%');
            })->latest('id')->paginate(10);
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
