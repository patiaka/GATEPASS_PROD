<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Department;
use Livewire\Attributes\Computed;

class UserList extends Component
{
    public string $role = "";
    public string $search = "";
    public string $department = "";

    public function ResetFilter(): void
    {
        $this->reset('department', 'role', 'search');
        $this->resetPage();
    }

    #[Computed]
    public function rows()
    {
        return User::when($this->search, function ($query) {
            $query->whereAny(['name', 'email'], 'like', '%' . $this->search . '%');
        })->when($this->department, function ($query) {
            $query->where('department_id', $this->department);
        })->when($this->role, function ($query) {
            $query->where('role', $this->role);
        })->latest('id')->paginate(10);
    }
    public function render()
    {
        $departments = Department::all();
        return view('livewire.user-list', compact('departments'));
    }
}
