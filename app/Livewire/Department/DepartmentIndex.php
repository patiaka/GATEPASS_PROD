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


    public function ResetFilter(): void
    {
        $this->reset('search');
    }

    public function delete(int $id): void
    {
        $row = Department::find($id);

        if (!$row) {
            flash()->error('Department not found.');
            return;
        }

        $row->delete();
        flash()->success('Department deleted with success');
    }

    #[Computed]
    public function rows()
    {
        return Department::select('id', 'name', 'created_at')
            ->when($this->search, function (Builder $query): void {
                $query->whereLike('name', "%{$this->search}%");
            })->latest()->paginate();
    }
}
