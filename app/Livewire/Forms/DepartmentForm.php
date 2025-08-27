<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Department;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class DepartmentForm extends Form
{
    public ?Department $department;

    #[Validate('required|string|unique:departments,name')]
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
        flash()->success('Department updated successfully');
    }
}
