<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Livewire\Forms\UserForm;
use App\Models\Department;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('edit user')]
final class UserUpdate extends Component
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
