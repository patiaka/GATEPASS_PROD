<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Department;
use Livewire\WithFileUploads;
use App\Livewire\Forms\UserForm;

class UserCreate extends Component
{

    public UserForm $form;

    public function save()
    {
        $this->form->store();
    }
    public function render()
    {
        $departments = Department::select('name', 'id')->get();
        return view('livewire.user.user-create', compact('departments'));
    }
}
