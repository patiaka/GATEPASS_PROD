<?php

namespace App\Livewire\Department;

use App\Livewire\Forms\DepartmentForm;
use App\Models\Department;
use Livewire\Component;

class DepartmentEdit extends Component
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
        return view('livewire.department.department-edit');
    }
}
