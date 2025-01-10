<?php

namespace App\Livewire;

use App\Models\Compagnie;
use App\Models\User;
use Livewire\Component;
use App\Models\Department;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;
    public string $role = "";
    public string $search = "";
    public string $department = "";
    public string $compagnie = "";

    public function ResetFilter(): void
    {
        $this->reset('department', 'role', 'search', 'compagnie');
    }

    #[Computed]
    public function rows()
    {
        return User::with('department:id,name', 'compagnie:id,name')->when($this->search, function ($query) {
            $query->whereAny(['name', 'email'], 'like', '%' . $this->search . '%');
        })->when($this->department, function ($query) {
            $query->where('department_id', $this->department);
        })->when($this->compagnie, function ($query) {
            $query->where('compagnie_id', $this->compagnie);
        })->when($this->role, function ($query) {
            $query->where('role', $this->role);
        })->latest('id')->paginate(10);
    }
    public function render()
    {
        $departments = Department::select('name', 'id')->get();
        $compagnies = Compagnie::select('name', 'id')->get();
        return view('livewire.user-list', compact('departments', 'compagnies'));
    }
}
