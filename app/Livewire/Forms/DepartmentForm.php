<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Department;
use Livewire\Attributes\Validate;

class DepartmentForm extends Form
{
    public ?Department $department;

    #[Validate('required|string')]
    public string $name = '';

    public function setDepartment(Department $department): void
    {
        $this->department = $department;

        $this->name = $department->name;
    }

    public function store(): void
    {
        $this->validate();
        Department::create($this->only(['name']));
        $this->reset();
        flash()->success('Department added successfully');
    }

    public function update(): void
    {
        $this->validate();

        $this->department->update($this->only(['name']));

        $this->reset();
        flash()->success('Department updated successfully');
    }
}
