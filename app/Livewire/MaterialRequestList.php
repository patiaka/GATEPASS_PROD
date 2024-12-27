<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Department;
use App\Models\MaterialRequest;
use App\Models\User;
use Livewire\Attributes\Computed;

class MaterialRequestList extends Component
{
    public string $search = "";
    public string $status = "";
    public string $department = "";
    public bool $selectAll = false;
    public array $selectedRows = [];
    public $bulkAction = 'delete'; // Action par défaut (peut être étendue)

    public function updatedSelectAll($value)
    {
        // Si tout est sélectionné, ajouter tous les IDs, sinon vider
        $this->selectedRows = $value ? $this->rows->pluck('id')->toArray() : [];
    }

    public function applyAction()
    {
        // Vérifier qu'une action et des lignes sont sélectionnées
        if (empty($this->selectedRows)) {
            session()->flash('error', 'Aucun élément sélectionné.');
            return;
        }

        switch ($this->bulkAction) {
            case 'delete':
                MaterialRequest::whereIn('id', $this->selectedRows)->delete();
                session()->flash('success', 'Les demandes sélectionnées ont été supprimées.');
                break;

            case 'approve':
                MaterialRequest::whereIn('id', $this->selectedRows)
                    ->update(['status' => 'Approved']);
                session()->flash('success', 'Les demandes sélectionnées ont été approuvées.');
                break;

                // Ajoutez d'autres cas si nécessaire
        }

        // Réinitialiser la sélection
        $this->selectedRows = [];
        $this->selectAll = false;
    }

    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search');
        $this->resetPage();
    }

    #[Computed]
    public function rows()
    {
        return MaterialRequest::with('user')->when($this->search, function ($query) {
            $query->whereAny(['name', 'email'], 'like', '%' . $this->search . '%');
        })->when($this->department, function ($query) {
            $query->where('department_id', $this->department);
        })->when($this->status, function ($query) {
            $query->where('status', $this->status);
        })->latest('id')->paginate(10);
    }

    public function render()
    {
        $departments = Department::all();
        $users = User::all();
        return view('livewire.material-request-list', \compact('departments', 'users'));
    }
}
