<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use Livewire\Component;
use App\Models\Department;
use Livewire\Attributes\Title;
use App\Livewire\Forms\DepartmentForm;

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
}
