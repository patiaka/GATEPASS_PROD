<?php

namespace App\Livewire\MaterialRequest;

use Auth;
use App\Models\User;
use Livewire\Component;
use App\Helper\WithFilter;
use App\Models\Department;
use App\Jobs\MailRequestJob;
use App\Helper\ApproveAction;
use App\Models\MaterialRequest;
use Livewire\Attributes\Computed;
use App\Enum\MaterialRequestStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

class Index extends Component
{
    use WithFilter;
    public $material;

    private function dispatchApprovalMail(Collection $items, string $role, string  $action): void
    {
        $items->each(function ($item) use ($role, $action) {
            $message = "le $role a $action votre request reference " . $item->reference;
            MailRequestJob::dispatch($item, $message);
        });
    }

    public function bulkAction(string $action): void
    {
        Gate::authorize('action-approved-request', Auth::user());
        $query = MaterialRequest::query()->whereIn('id', $this->selectedRows);
        if ($action === 'reject') {
            if (Auth::user()->isHod()) {
                $query->where('status', MaterialRequestStatus::Pending)->update([
                    'status' => MaterialRequestStatus::Rejected,
                    'hod_approval_id' => Auth::user()->id,
                ]);
                $this->dispatchApprovalMail($query->get(), 'hod', 'rejeté');
            } elseif (Auth::user()->isGm()) {
                $query->where('status', MaterialRequestStatus::Progress)->update([
                    'status' => MaterialRequestStatus::Rejected,
                    'gm_approval_id' => Auth::user()->id,
                ]);
                $this->dispatchApprovalMail($query->get(), 'gm', 'rejeté');
            }
        } elseif ($action === 'approve') {
            if (Auth::user()->isHod()) {
                $query->where('status', MaterialRequestStatus::Pending)->update([
                    'status' => MaterialRequestStatus::Progress,
                    'hod_approval_id' => Auth::user()->id,
                ]);
                $this->dispatchApprovalMail($query->get(), 'hod', 'validé');
            } elseif (Auth::user()->isGm()) {
                $query->where('status', MaterialRequestStatus::Progress)->update([
                    'status' => MaterialRequestStatus::Approved,
                    'gm_approval_id' => Auth::user()->id,
                    'expire_at' =>  Carbon::now()->addDays(7),
                ]);
                $this->dispatchApprovalMail($query->get(), 'gm', 'validé');
            }
        }
        $this->reset('selectedRows');
        flash($action . ' applied items successfully.');
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
