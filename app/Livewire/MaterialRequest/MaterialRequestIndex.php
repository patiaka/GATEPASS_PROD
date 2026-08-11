<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

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
        $this->reset('department', 'status', 'search', 'by_status', 'period', 'debut', 'fin');
    }

    #[Computed]
    public function rows()
    {
        $auth = Auth::user();
        $auth->loadMissing('department', 'department.users');

        $query = MaterialRequest::with(['user.department', 'hodApproval', 'gmApproval'])
            // ---- VISIBILITÉ PAR RÔLE (même logique que le Dashboard) ----
            ->visibleTo($auth)
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
            ->tap(fn ($query) => $this->applyPeriod($query))
        ;

        return $query->orderByDesc('id')->paginate(10);
    }

    public function delete(int $id): void
    {
        $row = MaterialRequest::with('documents')->find($id);

        if (! $row) {
            flash()->error('Material request not found.');

            return;
        }

        Gate::authorize('delete-request', $row);

        foreach ($row->documents as $document) {
            $this->file_delete($document);
            $document->delete();
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
