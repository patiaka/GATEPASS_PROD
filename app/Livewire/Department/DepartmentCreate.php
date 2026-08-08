<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Enum\RoleEnum;
use App\Livewire\Forms\DepartmentForm;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Department Off Site Form')]
final class DepartmentCreate extends Component
{
    public DepartmentForm $form;

    public function save()
    {
        $this->form->store();
    }

    public function render()
    {
        return view('livewire.department.department-create',[
            'users' => User::whereHas('roleAssignments', fn ($q) => $q->where('role', RoleEnum::DIRECTOR->value))->orderBy('name', 'ASC')->get(),
        ]);
    }
}
