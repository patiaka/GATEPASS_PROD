<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Helper\WithFilter;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('All Departments')]
final class DepartmentIndex extends Component
{
    use WithFilter;

    public function ResetFilter(): void
    {
        $this->reset('search');
    }

    public function delete(int $id): void
    {
        $row = Department::with('users')->find($id);

        if (! $row) {
            flash()->error('Department not found.');

            return;
        }

        if ($row->users->count() > 0) {
            flash()->error(sprintf('Department %s has users, delete them first.', strtoupper($row->name)));

            return;
        }

        $row->delete();
        flash()->success('Department deleted with success');
    }

    #[Computed]
    public function rows()
    {
        return Department::with('director')
            ->select('id', 'name', 'director_id', 'created_at')
            ->when($this->search, function (Builder $query): void {
                $query->whereLike('name', "%{$this->search}%");
            })->latest()->paginate()
        ;
    }
}
