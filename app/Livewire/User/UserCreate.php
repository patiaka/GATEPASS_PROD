<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Livewire\Forms\UserForm;
use App\Models\Department;
use Livewire\Component;

final class UserCreate extends Component
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
