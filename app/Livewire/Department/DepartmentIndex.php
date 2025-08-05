<?php

namespace App\Livewire\Department;

use Livewire\Component;
use App\Helper\WithFilter;
use App\Models\Department;
use Livewire\Attributes\Computed;
use App\Livewire\Forms\DepartmentForm;
use Illuminate\Database\Eloquent\Builder;

class DepartmentIndex extends Component
{
    use WithFilter;

    public DepartmentForm $form;


    public function edit(int $id): void
    {
        $this->form_type = true;
        $this->form->setDepartment(Department::findOrFail($id));
        $this->dispatch('open-edit-modal', id: $id);
    }

    public function save()
    {
        $this->form_type ? $this->form->update() : $this->form->store();
    }

    public function delete(int $id): void
    {
        $row = Department::find($id);

        if (!$row) {
            flash()->error('Department introuvable.');
            return;
        }

        $row->delete();
        flash()->success('Department supprimé avec succès');
    }

    #[Computed]
    public function rows()
    {
        return Department::select('id', 'name', 'created_at')
            ->when($this->search, function (Builder $query): void {
                $query->whereLike('nom', "%{$this->search}%");
            })->latest()->paginate();
    }
}
