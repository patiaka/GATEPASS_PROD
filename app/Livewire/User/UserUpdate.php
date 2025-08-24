<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use App\Models\Department;
use App\Livewire\Forms\UserForm;

class UserUpdate extends Component
{
    public User $user;
    public UserForm $form;


    public function mount(User $user)
    {
        $this->user = $user;
        $this->form->setUser($user);
    }

    public function save()
    {
        $this->form->update();
    }
    public function render()
    {
        $departments = Department::select('name', 'id')->get();
        return view('livewire.user.user-update', compact('departments'));
    }
}
