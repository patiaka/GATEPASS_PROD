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

    public function updateSelect($model, $value)
    {
        dd($model, $value);
    }

    #[Computed]
    public function rows()
    {
        return User::with('department:id,name')->when($this->search, function ($query) {
            $query->whereAny(['name', 'email'], 'like', '%' . $this->search . '%');
        })->when($this->department, function ($query) {
            dd('kk');
            $query->where('department_id', $this->department);
        })->when($this->role, function ($query) {
            dd('kksk');
            $query->where('role', $this->role);
        })->latest('id')->paginate(10);
    }
    public function render()
    {
        $departments = Department::all();
        return view('livewire.user-list', compact('departments'));
    }
}
