<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Enum\RoleEnum;
use App\Livewire\Forms\DepartmentForm;
use App\Models\Department;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Department')]
final class DepartmentEdit extends Component
{
    public Department $department;

    public DepartmentForm $form;

    public function mount(Department $department)
    {
        $this->department = $department;
        $this->form->setDepartment($department);
    }

    public function save()
    {
        $this->form->update();
    }

    public function render()
    {
        return view('livewire.department.department-edit',[
            'users' => User::whereHas('roleAssignments', fn ($q) => $q->where('role', RoleEnum::DIRECTOR->value))->orderBy('name', 'ASC')->get(),
        ]);
    }
}
