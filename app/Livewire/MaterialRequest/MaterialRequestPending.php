<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

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

        // En attente de MON action (même logique que le compteur du Dashboard)
        if ($auth->isApprover()) {
            $query->awaitingApprovalBy($auth);
        } elseif ($auth->isAdmin()) {
            // Admin : toutes les demandes encore en cours d'approbation
            $query->whereNotNull('next_approver_role');
        } else {
            // Autres rôles : leurs propres demandes en cours d'approbation
            $query->whereNotNull('next_approver_role')->where('user_id', $auth->id);
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
