<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Helper\ApproveAction;
use App\Helper\WithFilter;
use App\Helper\WithSorting;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

use function compact;

#[Title('Pending vehicle request')]
final class CarRequestPending extends Component
{
    use ApproveAction, WithFilter, WithSorting;


    public function ResetFilter(): void
    {
        $this->reset('search', 'sortField', 'sortDirection');
    }

    #[Computed]
    public function rows()
    {
        $auth = Auth::user();

        $query = CarRequest::with('user', 'user.department', 'hodApproval', 'gmApproval');

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

        $sortable = [
            'reference' => 'reference',
            'date' => 'created_at',
            'company' => 'company',
            'department' => fn ($q, $dir) => $q->orderBy(
                Department::select('name')->whereIn(
                    'departments.id',
                    User::select('department_id')->whereColumn('users.id', 'car_requests.user_id')
                ),
                $dir
            ),
        ];

        return $this->applySort($query, $sortable, fn ($q) => $q->latest('id'))->paginate(10);
    }

    public function render()
    {

        $auth = Auth::user();
        $departments = $auth->isAdmin() ? Department::select('id', 'name')->get() : [];

        return view('livewire.car-request.car-request-pending', compact('departments'));
    }
}
